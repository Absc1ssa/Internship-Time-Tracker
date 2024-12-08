<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Intern;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AdminController extends Controller
{

    public function dashboard()
    {
        // Fetch totals from the database
        $totalOffices = Office::count();
        $totalInterns = Intern::count();

        // Data for the column chart
        $officeData = Office::withCount('interns')->get();

        return view('admin.dashboard', compact('totalOffices', 'totalInterns', 'officeData'));
    }

    public function office()
    {
        return view('admin.offices');
    }

    // public function intern()
    // {
    //     return view('admin.interns');
    // }

    public function attendance(Request $request)
    {
        // Fetch offices for filtering
        $offices = Office::all();

        // Get filters and sorting inputs
        $selectedOffice = $request->input('office');
        $searchQuery = $request->input('search', '');
        $sortField = $request->input('sort', 'fname'); // Default sort by first name
        $sortDirection = $request->input('direction', 'asc'); // Default sort direction

        // Query for interns with related data including attendance
        $query = Intern::with(['user', 'office', 'attendance']);

        // Filter by office if selected
        if ($selectedOffice) {
            $query->where('office_id', $selectedOffice);
        }

        // Filter by search query
        if ($searchQuery) {
            $query->whereHas('user', function ($q) use ($searchQuery) {
                $q->where('fname', 'LIKE', "%$searchQuery%")
                    ->orWhere('lname', 'LIKE', "%$searchQuery%");
            });
        }

        // Apply sorting
        if (in_array($sortField, ['fname', 'lname'])) {
            $query->join('users', 'interns.user_id', '=', 'users.id')
                ->orderBy("users.$sortField", $sortDirection);
        }

        // Paginate the results
        $interns = $query->paginate(5)->appends($request->query());

        return view('admin.attendance', compact('interns', 'offices', 'selectedOffice', 'searchQuery', 'sortField', 'sortDirection'));
    }



    public function report(Request $request)
    {
        // Fetch all offices for filtering
        $offices = Office::all();

        // Get selected office filter
        $selectedOffice = $request->input('office');

        // Sorting fields and direction
        $sortField = $request->input('sort', 'fname'); // Default sorting field
        $sortDirection = $request->input('direction', 'asc'); // Default sorting direction

        // Dynamically determine the internship period from attendance records
        $startDate = Attendance::min('date'); // Earliest attendance date
        $endDate = Attendance::max('date'); // Latest attendance date

        // Handle cases where no attendance records exist
        if (!$startDate || !$endDate) {
            $startDate = Carbon::now(); // Default to current date
            $endDate = Carbon::now(); // Default to current date
        } else {
            $startDate = Carbon::parse($startDate);
            $endDate = Carbon::parse($endDate);
        }

        // Calculate total working weekdays in the internship period
        $workingDays = $startDate->diffInWeekdays($endDate);

        // Query users with related data
        $query = User::whereHas('intern')->with(['intern.office', 'intern.attendance']);

        // Filter by selected office if provided
        if ($selectedOffice) {
            $query->whereHas('intern', function ($q) use ($selectedOffice) {
                $q->where('office_id', $selectedOffice);
            });
        }

        // Apply sorting if valid fields are provided
        if (in_array($sortField, ['fname', 'lname'])) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Paginate the results
        $users = $query->paginate(10)->appends($request->query());

        // Calculate present and absent days for each user
        $users->each(function ($user) use ($workingDays) {
            $presentDays = $user->intern->attendance->filter(function ($attendance) {
                return !$attendance->date->isWeekend(); // Exclude weekends
            })->count();

            $absentDays = $workingDays - $presentDays;

            // Attach these values to the user model for easy access in the view
            $user->intern->present_days = $presentDays;
            $user->intern->absent_days = $absentDays;
        });

        // Return view with data
        return view('admin.reports', compact('users', 'offices', 'selectedOffice', 'sortField', 'sortDirection'));
    }
}
