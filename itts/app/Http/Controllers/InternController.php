<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Intern;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Flasher\Notyf\Prime\NotyfInterface;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Pagination\LengthAwarePaginator;

class InternController extends Controller
{
    protected $notyf;

    public function __construct(NotyfInterface $notyf)
    {
        $this->notyf = $notyf;
    }

    //Fetching Location
    private function getInternWithLocation()
    {
        $intern = Intern::with('office')->where('user_id', Auth::id())->first();
        $location = $intern && $intern->office ? $intern->office->name : 'No location assigned';
        return [$intern, $location];
    }

    // public function dashboard()
    // {
    //     $userId = Auth::id(); // Get the logged-in user's ID
    //     $today = now()->toDateString(); // Current date

    //     // Fetch today's attendance record
    //     $attendance = Attendance::where('user_id', $userId)->where('date', $today)->first();

    //     // Determine the status
    //     $status = 'clock-in'; // Default action is clock-in
    //     if ($attendance) {
    //         if ($attendance->am_clock_in && !$attendance->am_clock_out) {
    //             $status = 'clock-out'; // AM clock-out needed
    //         } elseif ($attendance->pm_clock_in && !$attendance->pm_clock_out) {
    //             $status = 'clock-out'; // PM clock-out needed
    //         } elseif ($attendance->am_clock_out && !$attendance->pm_clock_in) {
    //             $status = 'clock-in'; // PM clock-in needed
    //         }
    //     }

    //     // Fetch intern details and location
    //     [$intern, $location] = $this->getInternWithLocation();

    //     // Pass variables to the view
    //     return view('dashboard', compact('location', 'intern', 'status'));
    // }

    public function dashboard()
    {
        $userId = Auth::id(); // Get the logged-in user's ID
        $today = now()->toDateString(); // Current date

        // Fetch today's attendance record
        $attendance = Attendance::where('user_id', $userId)->where('date', $today)->first();

        // Determine the status
        $status = 'clock-in'; // Default action is clock-in
        if ($attendance) {
            if ($attendance->am_clock_in && !$attendance->am_clock_out) {
                $status = 'clock-out'; // AM clock-out needed
            } elseif ($attendance->pm_clock_in && !$attendance->pm_clock_out) {
                $status = 'clock-out'; // PM clock-out needed
            } elseif ($attendance->am_clock_out && !$attendance->pm_clock_in) {
                $status = 'clock-in'; // PM clock-in needed
            }
        }

        // Fetch intern details and location
        [$intern, $location] = $this->getInternWithLocation();

        // Pass variables to the view
        return view('dashboard', compact('location', 'intern', 'status'));
    }

    public function profile_attendance()
    {
        // Get the logged-in user
        $user = Auth::user();

        // Get the intern's record
        $intern = Intern::where('user_id', $user->id)->first();

        $avatar = $intern && $intern->avatar
            ? asset('storage/' . $intern->avatar)
            : ($user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->fname . ' ' . $user->lname) . '&background=0D8ABC&color=fff');

        // Fetch attendance records for the user
        $attendance = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        // Location logic if needed
        [$intern, $location] = $this->getInternWithLocation();

        // Return the view
        return view('profile-attendance', compact('avatar', 'attendance', 'location'));
    }


    //Add Intern
    public function store(Request $request)
    {
        $request->validate([
            'lname' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'fname' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'nullable|string|min:6',
            'office_id' => 'required|exists:offices,id',
            'avatar' => 'nullable|image|max:2048',
        ], [], [
            'fname' => 'first name',
            'lname' => 'last name',
            'email' => 'email address',
            'office_id' => 'office',
            'avatar' => 'avatar',
        ]);

        $user = User::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => 'intern',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        Intern::create([
            'user_id' => $user->id,
            'office_id' => $request->input('office_id'),
            'avatar' => $avatarPath,
        ]);

        notyf('Intern added successfully.');
        return redirect()->route('admin.interns');
    }

    //Fetch & Display Intern
    // public function index(Request $request)
    // {
    //     $selectedOffice = $request->input('office', '');
    //     $searchQuery = $request->input('search', '');
    //     $sortField = $request->input('sort', 'fname');
    //     $sortDirection = $request->input('direction', 'asc');

    //     $query = Intern::query()
    //         ->with(['user', 'office']) //, 'attendance'
    //         ->join('users', 'interns.user_id', '=', 'users.id')
    //         ->select('interns.*', 'users.fname', 'users.lname', 'users.email');

    //     // Filter by office
    //     if (!empty($selectedOffice)) {
    //         $query->where('interns.office_id', $selectedOffice);
    //     }

    //     // Filter by search query (fname and lname)
    //     if (!empty($searchQuery)) {
    //         $query->where(function ($q) use ($searchQuery) {
    //             $q->where('users.fname', 'LIKE', "%$searchQuery%")
    //                 ->orWhere('users.lname', 'LIKE', "%$searchQuery%");
    //         });
    //     }

    //     // Sort results
    //     if ($sortField === 'remaining_hours') {
    //         $query->orderBy('remaining_hours', $sortDirection);
    //     } else {
    //         $query->orderBy('users.' . $sortField, $sortDirection);
    //     }

    //     // Paginate results
    //     $interns = $query->paginate(10)->appends($request->query());
    //     $offices = Office::all();

    //     // Add total working hours for each intern
    //     // foreach ($interns as $intern) {
    //     //     $intern->total_working_hours = $this->getTotalWorkingHours($intern->user_id);
    //     // }

    //     return view('admin.interns', compact('interns', 'offices', 'selectedOffice', 'searchQuery', 'sortField', 'sortDirection'));
    // }

    public function index(Request $request)
    {
        $selectedOffice = $request->input('office', '');
        $searchQuery = $request->input('search', '');
        $sortField = $request->input('sort', 'fname');
        $sortDirection = $request->input('direction', 'asc');
        $remainingHoursFilter = $request->input('remaining_hours', '');

        $query = Intern::query()
            ->with(['user', 'office', 'attendance'])
            ->join('users', 'interns.user_id', '=', 'users.id')
            ->select('interns.*', 'users.fname', 'users.lname', 'users.email');

        // Filter by office
        if (!empty($selectedOffice)) {
            $query->where('interns.office_id', $selectedOffice);
        }

        // Filter by search query
        if (!empty($searchQuery)) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('users.fname', 'LIKE', "%$searchQuery%")
                    ->orWhere('users.lname', 'LIKE', "%$searchQuery%");
            });
        }

        // Calculate remaining hours for filtering
        $interns = $query->get(); // Get data to calculate remaining hours

        $interns->each(function ($intern) {
            $totalWorkingHours = $intern->attendance->reduce(function ($carry, $attend) {
                $amHours = $attend->am_clock_in && $attend->am_clock_out
                    ? Carbon::parse($attend->am_clock_in)->diffInMinutes(Carbon::parse($attend->am_clock_out)) / 60
                    : 0;
                $pmHours = $attend->pm_clock_in && $attend->pm_clock_out
                    ? Carbon::parse($attend->pm_clock_in)->diffInMinutes(Carbon::parse($attend->pm_clock_out)) / 60
                    : 0;
                return $carry + $amHours + $pmHours;
            }, 0);

            $intern->remaining_hours = 486 - $totalWorkingHours; // Replace with 486 in production
        });

        // Apply remaining hours filter
        if (!empty($remainingHoursFilter)) {
            $interns = $interns->filter(function ($intern) use ($remainingHoursFilter) {
                if ($remainingHoursFilter == '50') {
                    return $intern->remaining_hours < 50;
                } elseif ($remainingHoursFilter == '100') {
                    return $intern->remaining_hours >= 50 && $intern->remaining_hours <= 100;
                } elseif ($remainingHoursFilter == '150') {
                    return $intern->remaining_hours > 100;
                }
                return true;
            });
        }

        // Apply sorting
        if ($sortField === 'remaining_hours') {
            $interns = $interns->sortBy(function ($intern) use ($sortDirection) {
                return $intern->remaining_hours * ($sortDirection === 'asc' ? 1 : -1);
            });
        } else {
            $interns = $interns->sortBy($sortField);
        }

        $offices = Office::all();

        return view('admin.interns', compact('interns', 'offices', 'selectedOffice', 'searchQuery', 'sortField', 'sortDirection', 'remainingHoursFilter'));
    }


    //Get id
    public function edit($id)
    {
        $intern = Intern::with('user')->findOrFail($id);
        $offices = Office::all();

        return view('admin.editIntern', compact('intern', 'offices'));
    }

    //Update Intern
    public function update(Request $request, $id)
    {
        $intern = Intern::with('user')->findOrFail($id);
        $user = $intern->user;

        // Validation rules
        $request->validate([
            'lname' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255', // Only allow letters and spaces
            'fname' => 'required|string|regex:/^[a-zA-Z\s]+$/|max:255', // Only allow letters and spaces
            'email' => 'required|email|unique:users,email,' . $user->id, // Exclude current user's email
            'password' => 'nullable|string|min:6', // Optional password, minimum length 6
            'office_id' => 'required|exists:offices,id',
            'avatar' => 'nullable|image|max:2048', // Optional, limit file size
        ], [
            'lname.regex' => 'The last name must only contain letters and spaces.',
            'fname.regex' => 'The first name must only contain letters and spaces.',
        ]);

        // Update user details
        $user->update([
            'lname' => $request->lname,
            'fname' => $request->fname,
            'email' => $request->email,
            // Only update password if it is provided
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            if ($intern->avatar) {
                Storage::disk('public')->delete($intern->avatar); // Delete old avatar if it exists
            }
            $avatarPath = $request->file('avatar')->store('avatars', 'public'); // Store new avatar
            $intern->avatar = $avatarPath;
        }

        // Update intern details
        $intern->update([
            'office_id' => $request->office_id,
        ]);

        // Success notification
        notyf('Intern updated successfully.');

        return redirect()->route('admin.interns'); // Redirect back to the interns list
    }

    //Delete Intern
    public function destroy($id)
    {
        $intern = Intern::findOrFail($id);

        // Delete associated avatar if exists
        if ($intern->avatar) {
            Storage::disk('public')->delete($intern->avatar);
        }

        // Delete intern and associated user
        $intern->user->delete();
        $intern->delete();

        notyf()->info('Intern deleted successfully.');
        return redirect()->route('admin.interns');
    }

    //Avatar Upload for Intern
    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
        ], [
            'avatar.required' => 'Please select an avatar to upload.',
            'avatar.image' => 'The file must be an image.',
            'avatar.mimes' => 'Allowed image types are jpeg, png, jpg, and gif.',
            'avatar.max' => 'The image size should not exceed 5MB.',
        ]);

        $intern = Intern::where('user_id', Auth::id())->first();

        if (!$intern) {
            return response()->json(['message' => 'Intern not found'], 404);
        }

        // Delete the old avatar if it exists
        if ($intern->avatar) {
            Storage::disk('public')->delete($intern->avatar);
        }

        // Store the new avatar
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $intern->avatar = $avatarPath;
        $intern->save();

        return response()->json([
            'message' => 'Avatar updated successfully!',
            'avatar' => asset('storage/' . $avatarPath),
        ]);
    }

    // public function clockInOut(Request $request)
    // {
    //     $currentTime = now();
    //     // $currentTime = Carbon::parse('2024-11-24 10:00:00'); // For testing, use the current time in your live environment
    //     $userId = Auth::id();
    //     $action = $request->input('action'); // 'clock-in' or 'clock-out'

    //     // Validate the request
    //     $request->validate([
    //         'image' => 'required|string', // Base64-encoded image
    //     ]);

    //     // Decode the Base64 image
    //     $imageData = $request->input('image');
    //     $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

    //     // Save the image
    //     $imageName = $userId . '_' . $action . '_' . now()->timestamp . '.png';
    //     $imagePath = 'clock-images/' . $imageName;
    //     Storage::disk('public')->put($imagePath, $decodedImage);

    //     // Fetch or create attendance record for today
    //     $attendance = Attendance::firstOrCreate(
    //         ['user_id' => $userId, 'date' => $currentTime->toDateString()],
    //         [
    //             'am_clock_in' => null,
    //             'am_clock_out' => null,
    //             'pm_clock_in' => null,
    //             'pm_clock_out' => null,
    //             'am_clock_in_image' => null,
    //             'am_clock_out_image' => null,
    //             'pm_clock_in_image' => null,
    //             'pm_clock_out_image' => null,
    //         ]
    //     );

    //     // Clock-in/Clock-out logic
    //     if ($action === 'clock-in') {
    //         // Check if the user has already clocked out for AM
    //         if (!$attendance->am_clock_out && $attendance->am_clock_in) {
    //             notyf()->error('You must clock out for the AM session before clocking in for the PM session.');
    //             return back();
    //         }

    //         if (!$attendance->am_clock_in) {
    //             // AM clock-in
    //             $attendance->am_clock_in = $currentTime;
    //             $attendance->am_clock_in_image = $imagePath;
    //             $message = 'AM Clock-in successful!';
    //         } elseif (!$attendance->pm_clock_in) {
    //             // PM clock-in
    //             $attendance->pm_clock_in = $currentTime;
    //             $attendance->pm_clock_in_image = $imagePath;
    //             $message = 'PM Clock-in successful!';
    //         } else {
    //             notyf()->error('You have already clocked in for both AM and PM.');
    //             return back();
    //         }

    //         $status = 'clock-out';  // Set the status to clock-out after clock-in
    //     } elseif ($action === 'clock-out') {
    //         if ($attendance->am_clock_in && !$attendance->am_clock_out) {
    //             // AM clock-out
    //             $attendance->am_clock_out = $currentTime;
    //             $attendance->am_clock_out_image = $imagePath;
    //             $message = 'AM Clock-out successful!';
    //         } elseif ($attendance->pm_clock_in && !$attendance->pm_clock_out) {
    //             // PM clock-out
    //             $attendance->pm_clock_out = $currentTime;
    //             $attendance->pm_clock_out_image = $imagePath;
    //             $message = 'PM Clock-out successful!';
    //         } else {
    //             notyf()->error('You must clock in before clocking out.');
    //             return back();
    //         }

    //         $status = 'clock-in';  // Set the status to clock-in after clock-out
    //     } else {
    //         notyf()->error('Invalid action.');
    //         return back();
    //     }

    //     // Save the updated attendance record
    //     $attendance->save();

    //     notyf($message);

    //     return redirect()->route('dashboard', ['status' => $status]); // Pass the status to the view
    // }


    // $currentTime = now();

    public function clockInOut(Request $request)
    {
        // $currentTime = Carbon::parse('2024-11-12 17:00:00'); // For testing, use the current time in your live environment
        $currentTime = now();
        $userId = Auth::id();
        $action = $request->input('action'); // 'clock-in' or 'clock-out'

        // Validate the request
        $request->validate([
            'image' => 'required|string', // Base64-encoded image
        ]);

        // Decode the Base64 image
        $imageData = $request->input('image');
        $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

        // Save the image
        $imageName = $userId . '_' . $action . '_' . now()->timestamp . '.png';
        $imagePath = 'clock-images/' . $imageName;
        Storage::disk('public')->put($imagePath, $decodedImage);

        // Fetch or create attendance record for today
        $attendance = Attendance::firstOrCreate(
            ['user_id' => $userId, 'date' => $currentTime->toDateString()],
            [
                'am_clock_in' => null,
                'am_clock_out' => null,
                'pm_clock_in' => null,
                'pm_clock_out' => null,
                'am_clock_in_image' => null,
                'am_clock_out_image' => null,
                'pm_clock_in_image' => null,
                'pm_clock_out_image' => null,
            ]
        );

        // Define time boundaries
        $amStart = $currentTime->copy()->setTime(7, 50);
        $amEnd = $currentTime->copy()->setTime(12, 30);
        $pmStart = $currentTime->copy()->setTime(13, 0);
        $pmEnd = $currentTime->copy()->setTime(17, 0);

        // Clock-in/Clock-out logic
        if ($action === 'clock-in') {
            if ($currentTime->between($amStart, $amEnd) && !$attendance->am_clock_in) {
                // AM clock-in
                $attendance->am_clock_in = $currentTime;
                $attendance->am_clock_in_image = $imagePath;
                $message = 'AM Clock-in successful!';
            } elseif ($currentTime->between($pmStart, $pmEnd) && !$attendance->pm_clock_in) {
                // PM clock-in
                if ($attendance->am_clock_in && !$attendance->am_clock_out) {
                    notyf()->error('You must clock out for AM before clocking in for PM.');
                    return back();
                }
                $attendance->pm_clock_in = $currentTime;
                $attendance->pm_clock_in_image = $imagePath;
                $message = 'PM Clock-in successful!';
            } else {
                notyf()->error('Clock-in not allowed at this time.');
                return back();
            }
        } elseif ($action === 'clock-out') {
            if (
                $attendance->am_clock_in && !$attendance->am_clock_out &&
                $currentTime->greaterThan($attendance->am_clock_in->addHour()) &&
                $currentTime->between($attendance->am_clock_in->addHour(), $amEnd)
            ) {
                // AM clock-out
                $attendance->am_clock_out = $currentTime;
                $attendance->am_clock_out_image = $imagePath;
                $message = 'AM Clock-out successful!';
            } elseif (
                $attendance->pm_clock_in && !$attendance->pm_clock_out &&
                $currentTime->greaterThan($attendance->pm_clock_in->addHour()) &&
                $currentTime->between($attendance->pm_clock_in->addHour(), $pmEnd)
            ) {
                // PM clock-out
                $attendance->pm_clock_out = $currentTime;
                $attendance->pm_clock_out_image = $imagePath;
                $message = 'PM Clock-out successful!';
            } else {
                notyf()->error('Clock-out not allowed at this time.');
                return back();
            }
        } else {
            notyf()->error('Invalid action.');
            return back();
        }

        // Save the updated attendance record
        $attendance->save();

        // Set null attendance for AM if the intern didn't clock in during AM hours
        if (!$attendance->am_clock_in && !$attendance->am_clock_out && $currentTime->greaterThan($amEnd)) {
            $attendance->am_clock_in = null;
            $attendance->am_clock_out = null;
        }

        notyf($message);

        return redirect()->route('dashboard');
    }

    // public function clockInOut(Request $request)
    // {
    //     $currentTime = Carbon::parse('2024-11-24 07:30:00'); // For testing, use the current time in your live environment
    //     // $currentTime = now();
    //     $userId = Auth::id();
    //     $action = $request->input('action'); // 'clock-in' or 'clock-out'

    //     // Validate the request
    //     $request->validate([
    //         'image' => 'required|string', // Base64-encoded image
    //     ]);

    //     // Decode the Base64 image
    //     $imageData = $request->input('image');
    //     $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $imageData));

    //     // Save the image
    //     $imageName = $userId . '_' . $action . '_' . now()->timestamp . '.png';
    //     $imagePath = 'clock-images/' . $imageName;
    //     Storage::disk('public')->put($imagePath, $decodedImage);

    //     // Fetch or create attendance record for today
    //     $attendance = Attendance::firstOrCreate(
    //         ['user_id' => $userId, 'date' => $currentTime->toDateString()],
    //         [
    //             'am_clock_in' => null,
    //             'am_clock_out' => null,
    //             'pm_clock_in' => null,
    //             'pm_clock_out' => null,
    //             'am_clock_in_image' => null,
    //             'am_clock_out_image' => null,
    //             'pm_clock_in_image' => null,
    //             'pm_clock_out_image' => null,
    //         ]
    //     );

    //     // Define time boundaries
    //     $amStart = $currentTime->copy()->setTime(8, 0); // AM clock-in starts at 8:00 AM
    //     $amEnd = $currentTime->copy()->setTime(12, 30); // AM clock-out ends at 12:30 PM
    //     $pmStart = $currentTime->copy()->setTime(13, 0); // PM clock-in starts at 1:00 PM
    //     $pmEnd = $currentTime->copy()->setTime(17, 0); // PM clock-out ends at 5:00 PM

    //     // Clock-in/Clock-out logic
    //     if ($action === 'clock-in') {
    //         if ($currentTime->lt($amStart)) {
    //             // Clock-in before 8 AM (will set to 8 AM)
    //             $attendance->am_clock_in = $amStart;
    //             $attendance->am_clock_in_image = $imagePath;
    //             $message = 'AM Clock-in successful!';
    //         } elseif ($currentTime->between($amStart, $amEnd) && !$attendance->am_clock_in) {
    //             // AM clock-in (valid between 8 AM and 12:30 PM)
    //             $attendance->am_clock_in = $currentTime;
    //             $attendance->am_clock_in_image = $imagePath;
    //             $message = 'AM Clock-in successful!';
    //         } elseif ($currentTime->between($pmStart, $pmEnd) && !$attendance->pm_clock_in) {
    //             // PM clock-in (valid between 1 PM and 5 PM)
    //             if ($attendance->am_clock_in && !$attendance->am_clock_out) {
    //                 notyf()->error('You must clock out for AM before clocking in for PM.');
    //                 return back();
    //             }

    //             // Prevent clock-in after 11 AM and before 1 PM
    //             if ($currentTime->between($amEnd, $pmStart)) {
    //                 notyf()->error('Clock-in not allowed at this time. You can only clock in between 8 AM - 11 AM for AM or after 1 PM for PM.');
    //                 return back();
    //             }

    //             $attendance->pm_clock_in = $currentTime;
    //             $attendance->pm_clock_in_image = $imagePath;
    //             $message = 'PM Clock-in successful!';
    //         } else {
    //             notyf()->error('Clock-in not allowed at this time.');
    //             return back();
    //         }
    //     } elseif ($action === 'clock-out') {
    //         // AM clock-out
    //         if ($attendance->am_clock_in && !$attendance->am_clock_out && $currentTime->greaterThan($attendance->am_clock_in->addHour()) && $currentTime->between($attendance->am_clock_in->addHour(), $amEnd)) {
    //             // AM clock-out only allowed if at least one hour has passed since clock-in
    //             $attendance->am_clock_out = $currentTime;
    //             $attendance->am_clock_out_image = $imagePath;
    //             $message = 'AM Clock-out successful!';
    //         } elseif ($attendance->pm_clock_in && !$attendance->pm_clock_out && $currentTime->greaterThan($attendance->pm_clock_in->addHour()) && $currentTime->between($attendance->pm_clock_in->addHour(), $pmEnd)) {
    //             // PM clock-out only allowed if at least one hour has passed since clock-in
    //             $attendance->pm_clock_out = $currentTime;
    //             $attendance->pm_clock_out_image = $imagePath;
    //             $message = 'PM Clock-out successful!';
    //         } else {
    //             notyf()->error('Clock-out not allowed at this time.');
    //             return back();
    //         }
    //     } else {
    //         notyf()->error('Invalid action.');
    //         return back();
    //     }

    //     // Save the updated attendance record
    //     $attendance->save();

    //     // Set null attendance for AM if the intern didn't clock in during AM hours
    //     if (!$attendance->am_clock_in && !$attendance->am_clock_out && $currentTime->greaterThan($amEnd)) {
    //         $attendance->am_clock_in = null;
    //         $attendance->am_clock_out = null;
    //     }

    //     notyf($message);

    //     return redirect()->route('dashboard');
    // }






    public function show($id)
    {
        // $dates = now();
        $intern = Intern::with(['user', 'office', 'attendance'])->findOrFail($id);
        $attendance = $intern->attendance->sortByDesc('date'); // Sort attendance by date

        // Calculate total working hours
        $totalWorkingHours = $attendance->reduce(function ($carry, $attend) {
            $amHours = $attend->am_clock_in && $attend->am_clock_out
                ? \Carbon\Carbon::parse($attend->am_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->am_clock_out)) / 60
                : 0;

            $pmHours = $attend->pm_clock_in && $attend->pm_clock_out
                ? \Carbon\Carbon::parse($attend->pm_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->pm_clock_out)) / 60
                : 0;

            return $carry + $amHours + $pmHours;
        }, 0);

        return view('admin.intern-details', compact('intern', 'attendance', 'totalWorkingHours'));
    }

    public function exportTimesheetPDF($id)
    {
        // Fetch intern, attendance, and calculate total working hours
        $intern = Intern::with(['user', 'office', 'attendance'])->findOrFail($id);
        $attendance = $intern->attendance->sortByDesc('date'); // Sort attendance by date
        $totalWorkingHours = $attendance->reduce(function ($carry, $attend) {
            $amHours = $attend->am_clock_in && $attend->am_clock_out
                ? \Carbon\Carbon::parse($attend->am_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->am_clock_out)) / 60
                : 0;
            $pmHours = $attend->pm_clock_in && $attend->pm_clock_out
                ? \Carbon\Carbon::parse($attend->pm_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->pm_clock_out)) / 60
                : 0;

            return $carry + $amHours + $pmHours;
        }, 0);

        $currentDate = Carbon::now()->format('F j, Y');

        // Load the Blade view and pass data
        $pdf = Pdf::loadView('admin.timesheet', compact('intern', 'attendance', 'totalWorkingHours', 'currentDate'));

        // Download the PDF
        return $pdf->download('timesheet_' . $intern->user->fname . '_' . $intern->user->lname . '.pdf');
    }
}
