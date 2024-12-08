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
        <h1 class="text-xl font-semibold">Interns</h1>

        <!-- Button Group (Add Intern and Search) -->
        <div class="flex flex-col sm:flex-row gap-4 sm:gap-3 my-4">
            <!-- Add Intern Button -->
            <button id="openInternModal"
                class="flex items-center justify-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                <span class="material-icons mr-2">add</span>
                <span class="text-sm font-medium">Add Intern</span>
            </button>

            <!-- Search Input with Search Button -->
            <div class="flex-1 sm:max-w-md ">
                <form method="GET" action="{{ route('admin.interns') }}"
                    class="flex items-center border border-gray-300 rounded-lg bg-white overflow-hidden">
                    <input type="text" name="search" class="flex-1 px-4 py-2 text-sm focus:outline-none"
                        placeholder="Search Interns..." value="{{ old('search', $searchQuery ?? '') }}" />
                    <button type="submit" class="px-4 py-2 text-blue-500 hover:text-blue-600 transition-colors">
                        <span class="material-icons">search</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Filters and Controls -->
        <div class="flex flex-wrap items-center justify-between mb-6 ">
            <!-- Office Filter -->
            <div class="flex-grow sm:flex-grow-0 sm:w-auto ">
                <form method="GET" action="{{ route('admin.interns') }}">
                    <select name="office" onchange="this.form.submit()"
                        class="w-full px-2 py-2 text-gray-700 bg-white border border-gray-200 rounded-lg 
                                shadow-md shadow-gray-400 cursor-pointer hover:border-gray-400 focus:outline-none focus:ring-2 
                                focus:ring-blue-400 focus:border-blue-400 transition-all duration-200">
                        <option value="" {{ empty($selectedOffice) ? 'selected' : '' }}>All Offices</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}" {{ $selectedOffice == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            


        {{-- Intern's Table --}}
        @include('admin.intern-table')

    </section>
</div>

@include('admin.footer')

{{-- Add Intern Modal --}}
@include ('admin.intern_add_modal')


{{-- Delete Intern Modal --}}
@include ('admin.intern_delete')
