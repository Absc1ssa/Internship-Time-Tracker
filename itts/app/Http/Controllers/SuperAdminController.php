<?php

namespace App\Http\Controllers;

use App\Models\Intern;
use App\Models\Office;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Flasher\Notyf\Prime\NotyfInterface;

class SuperAdminController extends Controller
{
    protected $notyf;

    public function __construct(NotyfInterface $notyf)
    {
        $this->notyf = $notyf;
    }

    // Dashboard Data
    // public function dashboard()
    // {
    //     // Calculate total super admins
    //     $superAdminCount = User::where('role', 'super_admin')->count();

    //     // Calculate total admins
    //     $adminCount = User::where('role', 'admin')->count();

    //     // Calculate total interns
    //     $internCount = Intern::count();

    //     // Calculate total offices
    //     $totalOffices = Office::count();

    //     // Pass the data to the view
    //     return view('super-admin.dashboard', compact('superAdminCount', 'adminCount', 'internCount', 'totalOffices'));
    // }

    // Dashboard Data
    public function dashboard()
    {
        // Calculate total super admins
        $superAdminCount = User::where('role', 'super_admin')->count();

        // Calculate total admins
        $adminCount = User::where('role', 'admin')->count();

        // Calculate total interns
        $internCount = Intern::count();

        // Calculate total offices
        $totalOffices = Office::count();

        // Calculate combined total of Super Admins and Admins
        $totalAdmins = $superAdminCount + $adminCount;

        // Pass the data to the view
        return view('super-admin.dashboard', compact('superAdminCount', 'adminCount', 'totalAdmins', 'internCount', 'totalOffices'));
    }


    // Add Admin
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'fname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\-]+$/',
                'max:255'
            ],
            'lname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\-]+$/',
                'max:255'
            ],
            'email' => 'required|email|unique:users,email',
            'password' => ['required', 'string', 'min:8', Rules\Password::defaults()],
        ], [
            'fname.regex' => 'The first name may only contain letters, spaces, and hyphens.',
            'lname.regex' => 'The last name may only contain letters, spaces, and hyphens.',
        ]);

        User::create([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin',
        ]);

        notyf('Admin added successfully.');

        return redirect()->back();
    }

    // Display Users
    public function displayUsers()
    {
        $users = User::whereIn('role', ['admin', 'super_admin'])->paginate(10);
        return view('super-admin.users', compact('users'));
    }

    // Edit User
    public function user_edit($id)
    {
        $user = User::findOrFail($id);
        return view('super-admin.users_edit', compact('user'));
    }

    // Update Data
    public function updateAdmin(Request $request, $id)
    {
        $request->validate([
            'fname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\-]+$/',
                'max:255'
            ],
            'lname' => [
                'required',
                'string',
                'regex:/^[a-zA-Z\s\-]+$/',
                'max:255'
            ],
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            // 'role' => 'required|string'
        ], [
            'fname.regex' => 'The first name may only contain letters, spaces, and hyphens.',
            'lname.regex' => 'The last name may only contain letters, spaces, and hyphens.',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'fname' => $request->fname,
            'lname' => $request->lname,
            'email' => $request->email,
            // 'role' => $request->role,
            'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
        ]);

        notyf('Data updated successfully!');

        return redirect()->route('super-admin.users');
    }

    // Delete Data
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        notyf()->info('Deleted successfully!');

        return redirect()->back();
    }
}
