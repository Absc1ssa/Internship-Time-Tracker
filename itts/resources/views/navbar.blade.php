<!-- Navbar -->
<nav class="bg-gradient-to-l from-blue-300 to-blue-700 shadow-md shadow-gray-500 fixed top-0 left-0 right-0 z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <img src="{{ asset('images/psu-logo.png') }}" alt="University Logo" class="h-8 w-8 sm:h-10 sm:w-10 mr-2 sm:mr-4 bg-white shadow-sm rounded-full">
                <span class="text-xs sm:text-lg font-bold text-white">Pangasinan State University</span>
            </div>

            <div id="navLinks" class="hidden md:flex md:space-x-4 lg:space-x-6 items-center">
                <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm font-medium text-white hover:bg-white hover:text-blue-700 hover:rounded-sm px-2 py-1">Home</a>
                <a href="{{ route('profile-attendance') }}" class="text-xs sm:text-sm font-medium text-white hover:bg-white hover:text-blue-700 hover:rounded-sm px-2 py-1">Profile and Attendance</a>
                <form method="POST" action="{{ route('logout') }}" class="inline-block">
                    @csrf
                    <button type="submit" class="text-xs sm:text-sm font-medium text-white hover:bg-white hover:text-blue-700 rounded-sm px-3 sm:px-4 py-1">Logout</button>
                </form>
            </div>

            <button id="menuToggle" class="text-white focus:outline-none md:hidden z-30">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>
    </div>
</nav>

<!-- Slide-in Mobile Menu with Full Width and Height -->
<div id="mobileMenu" class="fixed inset-0 w-full h-full bg-blue-700 bg-opacity-90 text-white p-4 transform translate-x-full slide-out md:hidden z-30">
    <div class="flex justify-end mb-4">
        <button id="closeMenu" class="text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>

    <div class="flex flex-col space-y-4 mt-8">
        {{-- <a href="{{ route('dashboard') }}" class="text-xs font-medium text-white">Home</a> --}}
        <a href="{{ route('profile-attendance') }}" class="text-xs font-medium text-white">Profile and Attendance</a>
        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" class="text-xs font-medium text-white rounded-sm mt-2">Logout</button>
        </form>
    </div>
</div>