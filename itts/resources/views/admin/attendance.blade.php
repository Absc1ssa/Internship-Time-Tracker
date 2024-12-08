{{-- Header - Links - Css --}}
@include('admin.links_css')

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust w-full">
    <!-- Fixed Header -->
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10 shadow-lg">
        <button id="toggle-btn" class="text-2xl hover:text-blue-200 transition-colors">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header and Title -->
            <div class="mb-6">
                <h1 class="text-xl font-semibold">Attendance</h1>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg shadow-gray-400 shadow-lg p-6">
                <!-- Filters Section -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Office Filter -->
                        <div class="relative">
                            <form method="GET" action="{{ route('admin.attendance') }}">
                                <select name="office" onchange="this.form.submit()"
                                    class="w-full px-2 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg 
                                shadow-sm cursor-pointer hover:border-blue-400 focus:outline-none focus:ring-2 
                                focus:ring-blue-400 focus:border-blue-400 transition-all duration-200">
                                    <option value="" {{ !$selectedOffice ? 'selected' : '' }}>All Offices</option>
                                    @foreach ($offices as $office)
                                        <option value="{{ $office->id }}"
                                            {{ $selectedOffice == $office->id ? 'selected' : '' }}>
                                            {{ $office->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                        </div>

                    </div>

                    <!-- Search Input with Search Button -->
                    <div class="flex-1 sm:max-w-md">
                        <form method="GET" action="{{ route('admin.attendance') }}"
                            class="flex items-center border border-gray-300 rounded-lg bg-white overflow-hidden">
                            <input type="hidden" name="office" value="{{ $selectedOffice }}">
                            <input type="text" name="search" class="flex-1 px-4 py-2 text-sm focus:outline-none"
                                placeholder="Search Interns..." value="{{ old('search', $searchQuery ?? '') }}" />
                            <!-- Search Button -->
                            <button type="submit"
                                class="px-2 py-2 text-blue-500 hover:text-blue-600 transition-colors">
                                <span class="material-icons">search</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-yellow-400 to-yellow-300">
                                <th rowspan="2"
                                    class="bg-gray-100 border border-gray-100 px-6 py-4 text-center text-sm font-semibold text-gray-700 tracking-wider border-b">
                                    <a href="{{ route('admin.attendance', [
                                        'sort' => 'fname',
                                        'direction' => $sortField === 'fname' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                        'office' => $selectedOffice,
                                        'search' => $searchQuery,
                                    ]) }}"
                                        class="flex items-center hover:text-gray-600">
                                        Name
                                        <span class="material-icons ml-auto">
                                            {{ $sortField === 'fname' ? ($sortDirection === 'asc' ? 'unfold_more' : 'unfold_more') : 'unfold_more' }}
                                        </span>
                                    </a>
                                </th>
                                <th colspan="2"
                                    class="bg-blue-500 border border-gray-100 px-6 py-4 text-center text-sm font-semibold text-white tracking-wider">
                                    Clock In
                                </th>
                                <th colspan="2"
                                    class="bg-blue-500 border border-gray-100 px-6 py-4 text-center text-sm font-semibold text-white tracking-wider">
                                    Clock Out
                                </th>
                                <th rowspan="2"
                                    class="bg-blue-500 border border-gray-100 px-6 py-4 text-center text-sm font-semibold text-white tracking-wider border-b">
                                    Working Hours
                                </th>
                                <th colspan="4"
                                    class="bg-blue-500 border border-gray-100 px-6 py-4 text-center text-sm font-semibold text-white tracking-wider">
                                    Taken Photos
                                </th>
                            </tr>

                            <tr class="bg-yellow-300">
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    AM
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    PM
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    AM
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    PM
                                </th>

                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    AM CLOCK IN
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    AM CLOCK OUT
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    PM CLOCK IN
                                </th>
                                <th
                                    class="border border-gray-100 px-6 py-2 text-center text-xs font-semibold text-gray-800 tracking-wider">
                                    PM CLOCK OUT
                                </th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($interns as $intern)
                                @php
                                    // Get the latest attendance record for this intern
                                    $lastAttendance = $intern->attendance->last();
                                @endphp
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Name, Avatar, and Office -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <!-- Avatar -->
                                            <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-200">
                                                @if ($intern->avatar)
                                                    <img src="{{ asset('storage/' . $intern->avatar) }}"
                                                        alt="{{ $intern->user->fname }}'s Avatar"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-gray-500 text-sm">person</span>
                                                @endif
                                            </div>
                                            <!-- Name and Office -->
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $intern->user->fname }} {{ $intern->user->lname }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    {{ $intern->office->name ?? 'No Office Assigned' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Clock In -->
                                    <td class="px-6 py-4 text-center">
                                        {{ $lastAttendance?->am_clock_in ? \Carbon\Carbon::parse($lastAttendance->am_clock_in)->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $lastAttendance?->pm_clock_in ? \Carbon\Carbon::parse($lastAttendance->pm_clock_in)->format('h:i A') : '—' }}
                                    </td>

                                    <!-- Clock Out -->
                                    <td class="px-6 py-4 text-center">
                                        {{ $lastAttendance?->am_clock_out ? \Carbon\Carbon::parse($lastAttendance->am_clock_out)->format('h:i A') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        {{ $lastAttendance?->pm_clock_out ? \Carbon\Carbon::parse($lastAttendance->pm_clock_out)->format('h:i A') : '—' }}
                                    </td>

                                    <!-- Working Hours -->
                                    <td class="px-6 py-4 text-center">
                                        @if ($lastAttendance)
                                            @php
                                                // Calculate AM hours worked
                                                $amStart = $lastAttendance->am_clock_in
                                                    ? \Carbon\Carbon::parse($lastAttendance->am_clock_in)
                                                    : null;
                                                $amEnd = $lastAttendance->am_clock_out
                                                    ? \Carbon\Carbon::parse($lastAttendance->am_clock_out)
                                                    : null;
                                                $amHours =
                                                    $amStart && $amEnd ? $amStart->diffInMinutes($amEnd) / 60 : 0;

                                                // Calculate PM hours worked
                                                $pmStart = $lastAttendance->pm_clock_in
                                                    ? \Carbon\Carbon::parse($lastAttendance->pm_clock_in)
                                                    : null;
                                                $pmEnd = $lastAttendance->pm_clock_out
                                                    ? \Carbon\Carbon::parse($lastAttendance->pm_clock_out)
                                                    : null;
                                                $pmHours =
                                                    $pmStart && $pmEnd ? $pmStart->diffInMinutes($pmEnd) / 60 : 0;

                                                // Total hours worked
                                                $totalHours = $amHours + $pmHours;
                                            @endphp
                                            {{ $totalHours > 0 ? number_format($totalHours, 2) . ' hrs' : '—' }}
                                        @else
                                            —
                                        @endif
                                    </td>

                                    <!-- Photos -->
                                    <td class="px-6 py-4 text-center">
                                        @if ($lastAttendance?->am_clock_in_image)
                                            <a href="{{ asset('storage/' . $lastAttendance->am_clock_in_image) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $lastAttendance->am_clock_in_image) }}"
                                                    alt="AM Clock In" class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($lastAttendance?->am_clock_out_image)
                                            <a href="{{ asset('storage/' . $lastAttendance->am_clock_out_image) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $lastAttendance->am_clock_out_image) }}"
                                                    alt="AM Clock Out" class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($lastAttendance?->pm_clock_in_image)
                                            <a href="{{ asset('storage/' . $lastAttendance->pm_clock_in_image) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $lastAttendance->pm_clock_in_image) }}"
                                                    alt="PM Clock In" class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($lastAttendance?->pm_clock_out_image)
                                            <a href="{{ asset('storage/' . $lastAttendance->pm_clock_out_image) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $lastAttendance->pm_clock_out_image) }}"
                                                    alt="PM Clock Out" class="w-16 h-16 rounded-md">
                                            </a>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="11" class="text-center py-4 text-gray-500">
                                        No attendance records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                    <div class="mt-4">
                        {{ $interns->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('admin.footer')
