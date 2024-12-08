{{-- Header - Links - Css --}}
@include('admin.links_css')

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust">
    <!-- Fixed Header -->
    <header class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">

        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.reports') }}"
                        class="flex items-center text-gray-700 hover:text-blue-600 space-x-2">
                        <span class="material-symbols-outlined text-lg">
                            bar_chart
                        </span>
                        <span class="text-sm font-semibold">Reports</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-2 text-gray-500 font-medium">Intern's Timesheet</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="container mx-auto p-6">
            <!-- Intern Details -->
            <div class="bg-white shadow-md shadow-gray-400 rounded-lg p-6 mb-6">
                <h1 class="text-2xl font-semibold mb-4">Intern Details</h1>
                <div class="flex items-center justify-between">
                    <!-- Left: Intern Name and Avatar -->
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-200">
                            @if ($intern->avatar)
                                <img src="{{ asset('storage/' . $intern->avatar) }}"
                                    alt="{{ $intern->user->fname }}'s Avatar" class="h-full w-full object-cover">
                            @else
                                <span class="material-symbols-outlined text-gray-500 text-3xl">person</span>
                            @endif
                        </div>
                        <div class="ml-4">
                            <h2 class="text-lg font-medium">{{ $intern->user->fname }} {{ $intern->user->lname }}</h2>
                            <p class="text-gray-600">{{ $intern->office->name ?? 'No Office Assigned' }}</p>
                        </div>
                    </div>
                    <!-- Right: Total Working Hours -->
                    <div class="text-right">
                        <p class="text-sm font-medium text-gray-500">Total Working Hours</p>
                        <p class="text-3xl font-bold text-blue-600">
                            {{ number_format($totalWorkingHours, 2) }} / 486 hrs
                        </p>
                    </div>
                </div>
            </div>

            <!-- Attendance Records -->
            <div class="bg-white shadow-md shadow-gray-400 rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">Attendance Records</h2>
                    <!-- Export Button -->
                    <a href="{{ route('timesheet.export.pdf', $intern->id) }}"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-colors flex items-center space-x-2">
                        <span class="material-symbols-outlined text-base">
                            file_export
                        </span>
                        <span>Export as PDF</span>
                    </a>
                </div>
                @if ($attendance->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-center font-semibold text-gray-700">Date</th>
                                    <th class="px-4 py-2 text-center font-semibold text-blue-600">AM Clock In</th>
                                    <th class="px-4 py-2 text-center font-semibold text-blue-600">AM Clock Out</th>
                                    <th class="px-4 py-2 text-center font-semibold text-blue-600">PM Clock In</th>
                                    <th class="px-4 py-2 text-center font-semibold text-blue-600">PM Clock Out</th>
                                    <th class="px-4 py-2 text-center font-semibold text-green-600">Daily Working Hours
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($attendance as $attend)
                                    <tr>
                                        <td class="px-4 py-2 text-center">{{ $attend->date }}</td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $attend->am_clock_in ? \Carbon\Carbon::parse($attend->am_clock_in)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $attend->am_clock_out ? \Carbon\Carbon::parse($attend->am_clock_out)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $attend->pm_clock_in ? \Carbon\Carbon::parse($attend->pm_clock_in)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            {{ $attend->pm_clock_out ? \Carbon\Carbon::parse($attend->pm_clock_out)->format('h:i A') : '—' }}
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            @php
                                                // Calculate AM working hours
                                                $amHours =
                                                    $attend->am_clock_in && $attend->am_clock_out
                                                        ? \Carbon\Carbon::parse($attend->am_clock_in)->diffInMinutes(
                                                                \Carbon\Carbon::parse($attend->am_clock_out),
                                                            ) / 60
                                                        : 0;

                                                // Calculate PM working hours
                                                $pmHours =
                                                    $attend->pm_clock_in && $attend->pm_clock_out
                                                        ? \Carbon\Carbon::parse($attend->pm_clock_in)->diffInMinutes(
                                                                \Carbon\Carbon::parse($attend->pm_clock_out),
                                                            ) / 60
                                                        : 0;

                                                $dailyHours = $amHours + $pmHours;
                                            @endphp
                                            {{ $dailyHours > 0 ? number_format($dailyHours, 2) . ' hrs' : '—' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-red-600 font-semibold">No attendance records found.</p>
                @endif
            </div>

        </div>

    </section>
</div>


{{-- Add Office Modal --}}
@include ('admin.office_add')

{{-- Delete Office Modal --}}
@include ('admin.office_delete')

@include('admin.footer')
