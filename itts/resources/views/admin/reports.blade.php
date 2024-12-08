{{-- Header - Links - Css --}}
@include('admin.links_css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust w-full">
    <!-- Fixed Header -->
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10 shadow-lg">
        <button id="toggle-btn" class="text-2xl hover:text-blue-200 transition-colors">☰</button>
        <h1 class="text-xl font-bold">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">
        <div class="max-w-7xl mx-auto">
            <!-- Header and Title -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Reports</h1>
            </div>

            <!-- Main Content Card -->
            <div class="bg-white rounded-lg shadow-gray-400 shadow-lg p-6">
                <!-- Filters Section -->
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <!-- Office Filter -->
                        <div class="relative">
                            <form method="GET" action="{{ route('admin.reports') }}">
                                <select name="office" onchange="this.form.submit()"
                                    class="w-full min-w-[200px] h-10 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
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

                        <!-- Date Filter -->
                        {{-- <div class="relative">
                            <input type="text" id="dateFilter"
                                class="w-full min-w-[200px] h-10 px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                                placeholder="Select Date">
                            <span
                                class="material-icons absolute right-3 top-2.5 text-gray-500 pointer-events-none">calendar_today</span>
                        </div> --}}
                    </div>

                    <!-- Export Button -->
                    {{-- <button class="flex items-center justify-center h-10 bg-blue-600 text-white rounded-lg px-6 py-2 hover:bg-blue-700 transition-colors">
                        <span class="material-symbols-outlined mr-2">download</span>
                        <span class="text-sm font-medium">Export Report</span>
                    </button> --}}
                </div>

                <!-- Table Section -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr class="bg-gradient-to-r from-yellow-400 to-yellow-300">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-800 tracking-wider">
                                    <a href="{{ route('admin.reports', [
                                        'sort' => 'fname',
                                        'direction' => $sortField === 'fname' && $sortDirection === 'asc' ? 'desc' : 'asc',
                                        'office' => $selectedOffice,
                                    ]) }}"
                                        class="flex items-center hover:text-gray-600">
                                        Name
                                        <span class="material-icons ml-4">
                                            {{ $sortField === 'fname' ? ($sortDirection === 'asc' ? 'unfold_more' : 'unfold_more') : 'unfold_more' }}
                                        </span>
                                    </a>
                                </th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-800 tracking-wider">
                                    Present</th>
                                <th class="px-6 py-4 text-center text-sm font-semibold text-gray-800 tracking-wider">
                                    Absent</th>
                            </tr>
                        </thead>

                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <!-- Name, Avatar, and Office -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <!-- Avatar -->
                                            <div class="h-8 w-8 rounded-full overflow-hidden bg-gray-200">
                                                @if ($user->intern->avatar)
                                                    <img src="{{ asset('storage/' . $user->intern->avatar) }}"
                                                        alt="{{ $user->fname }}'s Avatar"
                                                        class="h-full w-full object-cover">
                                                @else
                                                    <span
                                                        class="material-symbols-outlined text-gray-500 text-sm">person</span>
                                                @endif
                                            </div>
                                            <!-- Name and Office -->
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('intern.details', $user->intern->id) }}"
                                                        class="text-blue-600 hover:text-blue-800">
                                                        {{ $user->fname }} {{ $user->lname }}
                                                    </a>
                                                </div>

                                                <div class="text-xs text-gray-500">
                                                    {{ $user->intern->office->name ?? 'No Office Assigned' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Placeholder for Present and Absent -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ $user->intern->present_days ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span
                                            class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                            {{ $user->intern->absent_days ?? 0 }}
                                        </span>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="flex flex-col items-center justify-center py-10">
                                            <img src="{{ asset('images/no-data-found.jpg') }}" alt="No Data Found"
                                                class="w-64 h-auto">
                                            <p class="text-gray-600 mt-4 text-lg">No users found for the selected
                                                office.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $users->links('pagination::tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@include('admin.footer')

<!-- Flatpickr Script -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Initialize Flatpickr
        flatpickr("#dateFilter", {
            dateFormat: "d - M - Y",
            onChange: function(selectedDates, dateStr, instance) {
                // Simulate table filtering based on date selection
                filterTableByDate(dateStr);
            }
        });

        // Function to filter the table based on date (simulation)
        function filterTableByDate(date) {
            console.log("Filtering table for date:", date);
            // Implement the actual filtering logic based on date selection
        }
    });
</script>
