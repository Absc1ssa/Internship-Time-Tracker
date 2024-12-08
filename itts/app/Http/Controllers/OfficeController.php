<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use Illuminate\Support\Facades\Storage;
use Flasher\Notyf\Prime\NotyfInterface;

class OfficeController extends Controller
{
    protected $notyf;

    public function __construct(NotyfInterface $notyf)
    {
        $this->notyf = $notyf;
    }

    // Add Office
    public function store(Request $request)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z\s\-]+$/', // Allows letters, numbers, spaces, and hyphens
            ],
            'location' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z0-9\s,.\-\/()]+$/',
            ],
            'photo' => 'nullable|image|max:2048', // Optional photo
        ], [
            'name.regex' => 'The office name may only contain letters, numbers, spaces, and hyphens.',
            'location.regex' => 'The location may only contain valid characters.',
        ]);

        // Handle photo upload
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('office_photos', 'public');
        }

        // Create the office record
        Office::create([
            'name' => $request->name,
            'location' => $request->location,
            'photo' => $photoPath,
        ]);

        notyf('Office added successfully.');

        // Redirect to the offices page with a success message
        return redirect()->route('admin.offices');
    }



    // Display Offices
    // public function index()
    // {
    //     $offices = Office::all();
    //     return view('admin.offices', compact('offices'));
    // }

    public function index()
    {
        // Eager load the interns relationship
        $offices = Office::with('interns')->get();
        return view('admin.offices', compact('offices'));
    }

    // Update Office
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|regex:/^[a-zA-Z\s\-]+$/',
            'location' => 'required|string|max:255|regex:/^[a-zA-Z0-9\s,.\-]+$/u',
            'photo' => 'nullable|image|max:2048',
        ]);

        $office = Office::findOrFail($id);

        if ($request->hasFile('photo')) {
            if ($office->photo) {
                Storage::disk('public')->delete($office->photo);
            }

            $photoPath = $request->file('photo')->store('office_photos', 'public');
            $office->photo = $photoPath;
        }

        $office->name = $request->input('name');
        $office->location = $request->input('location');
        $office->save();

        notyf('Office updated successfully.');

        return redirect()->route('admin.offices');
    }

    // Delete Office
    public function destroy($id)
    {
        $office = Office::findOrFail($id);

        if ($office->photo) {
            Storage::disk('public')->delete($office->photo);
        }

        $office->delete();

        notyf()->info('Office deleted successfully.');

        return redirect()->route('admin.offices');
    }

    public function edit($id)
    {
        $office = Office::findOrFail($id); // Find the office by ID
        return view('admin.office_edit', compact('office')); // Pass the office data to the view
    }
}
