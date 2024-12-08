{{-- Sidebar --}}
<div id="sidebar" class="w-64 bg-gray-800 text-white fixed h-full transition-all pt-16">
    <div class="logo-container px-4">
        <img style="background-color: white; border-radius: 50%;" src="{{ asset('images/psu-logo.png') }}"
            alt="PSU Logo" loading="lazy" class="w-8 h-8 object-contain shadow-lg">
        <p class="text-md font-medium">Internship Time Tracker</p>
    </div>
    <ul class="px-4">
        <li class="py-2">
            <a href="{{ route('admin.dashboard') }}"
               class="text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors flex items-center
                      {{ request()->routeIs('admin.dashboard') ? 'bg-blue-700 px-2 rounded-sm' : '' }}">
                <span class="material-icons">dashboard</span>
                <span class="ml-2">Dashboard</span>
            </a>
        </li>
        <li class="py-2">
            <a href="{{ route('admin.offices') }}"
               class="text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors flex items-center
                      {{ request()->routeIs('admin.offices') ? 'bg-blue-700 px-2 rounded-sm' : '' }}">
                <span class="material-icons">business</span>
                <span class="ml-2">Offices</span>
            </a>
        </li>
        <li class="py-2">
            <a href="{{ route('admin.interns') }}"
               class="text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors flex items-center
                      {{ request()->routeIs('admin.interns') ? 'bg-blue-700 px-2 rounded-sm' : '' }}">
                <span class="material-icons">people</span>
                <span class="ml-2">Interns</span>
            </a>
        </li>
        <li class="py-2">
            <a href="{{ route('admin.attendance') }}"
               class="text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors flex items-center
                      {{ request()->routeIs('admin.attendance') ? 'bg-blue-700 px-2 rounded-sm' : '' }}">
                <span class="material-icons">event</span>
                <span class="ml-2">Attendance</span>
            </a>
        </li>
        <li class="py-2">
            <a href="{{ route('admin.reports') }}"
               class="text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors flex items-center
                      {{ request()->routeIs('admin.reports') ? 'bg-blue-700 px-2 rounded-sm' : '' }}">
                <span class="material-icons">bar_chart</span>
                <span class="ml-2">Reports</span>
            </a>
        </li>
        <li class="py-2">
            <!-- Trigger modal with a checkbox -->
            <label for="logoutModalToggle"
                   class="flex items-center text-lg text-white hover:px-2 hover:text-white hover:bg-blue-700 hover:rounded-sm transition-colors cursor-pointer">
                <span class="material-icons">logout</span>
                <span class="ml-2">Logout</span>
            </label>
        </li>
    </ul>
</div>


<!-- Hidden checkbox to toggle the modal -->
<input type="checkbox" id="logoutModalToggle" class="hidden peer">

<!-- Logout Confirmation Modal -->
<div class="fixed inset-0 bg-black bg-opacity-50 z-50 justify-center items-center hidden peer-checked:flex">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-1/3">
        <div class="flex justify-center items-center mx-auto h-10 w-10 bg-red-100 rounded-3xl">
            <div class="flex justify-center items-center h-7 w-7 bg-red-300 rounded-3xl">
                <p class="text-red-800 text-xl font-bold">!</p>
            </div>
        </div>
        <h2 class="text-xl font-semibold text-center text-gray-800 mt-4 mb-4">Logout Account</h2>
        <p class="text-gray-600 mb-6 text-sm text-center">Are you sure you want to log out?</p>
        <div class="flex justify-end space-x-4">

            <label for="logoutModalToggle" class="bg-gray-300 hover:bg-gray-400 text-black text-xs h-full px-4 py-2 rounded-sm cursor-pointer">Cancel</label>
            
            <!-- Form submit button inside modal -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-xs h-full text-white px-4 py-2 rounded-sm">Logout</button>
            </form>
            <!-- Cancel Button: Unchecks the checkbox, hiding the modal -->
        </div>
    </div>
</div>