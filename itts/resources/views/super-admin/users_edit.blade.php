{{-- Header - Links - Css --}}
@include('super-admin.header_links')

{{-- Sidebar --}}
@include('super-admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust bg-gray-200">
    <!-- Fixed Header -->
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Super Admin Dashboard</h1>
    </header>

    <!-- Dashboard content with reduced padding -->
    <div class="max-w-4xl mx-auto p-6">

        <!-- Enhanced Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('super-admin.users') }}"
                        class="flex items-center text-gray-700 hover:text-blue-600 space-x-2">
                        <!-- Office Icon -->
                        <span class="material-symbols-outlined text-lg text-black">
                            admin_panel_settings
                        </span>
                        <span class="text-sm font-semibold">Admins</span>
                    </a>

                </li>
                <li>
                    <div class="flex items-center">
                        <!-- Chevron Icon -->
                        <svg class="w-5 h-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-2 text-gray-500 font-medium">Edit Admins</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="bg-gray-50 rounded-xl shadow-lg shadow-gray-400 overflow-hidden">

            <section class="bg-gray-50">
                <div class="py-8  px-4 mx-auto max-w-screen-sm">
                    <h1
                        class="mb-6 lg:mb-8 text-lg lg:text-2xl font-extrabold text-center text-gray-900 dark:text-white">
                        Update Admin Details
                    </h1>

                    <form action="{{ route('super-admin.updateAdmin', ['id' => $user->id]) }}" method="POST"
                        class="space-y-4 lg:space-y-6">
                        @csrf
                        @method('POST') <!-- Use PUT or PATCH here if updating -->

                        <!-- Last Name and First Name Grouped -->
                        <div class="sm:flex sm:space-x-4 mb-4">
                            <!-- Last Name -->
                            <div class="w-full sm:w-1/2 mb-4 sm:mb-0">
                                <label
                                    class="block text-gray-700 text-xs sm:text-sm lg:text-md font-medium mb-1 lg:mb-2"
                                    for="last-name">Last Name</label>
                                <input name="lname" type="text" id="last-name" value="{{ $user->lname }}"
                                    class="w-full px-3 py-2 border {{ $errors->has('lname') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs sm:text-sm lg:text-md"
                                    placeholder="Enter last name" required>
                                @error('lname')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- First Name -->
                            <div class="w-full sm:w-1/2">
                                <label
                                    class="block text-gray-700 text-xs sm:text-sm lg:text-md font-medium mb-1 lg:mb-2"
                                    for="first-name">First Name</label>
                                <input name="fname" type="text" id="first-name" value="{{ $user->fname }}"
                                    class="w-full px-3 py-2 border {{ $errors->has('fname') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs sm:text-sm lg:text-md"
                                    placeholder="Enter first name" required>
                                @error('fname')
                                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- <!-- Role -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs sm:text-sm lg:text-md font-medium mb-1 lg:mb-2"
                                for="role">Role</label>
                            <select name="role" id="role"
                                class="w-full px-3 py-2 border {{ $errors->has('role') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs sm:text-sm lg:text-md">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="super_admin" {{ $user->role == 'super_admin' ? 'selected' : '' }}>
                                    Super Admin
                                </option>
                            </select>
                            @error('role')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs sm:text-sm lg:text-md font-medium mb-1 lg:mb-2"
                                for="email">Email</label>
                            <input name="email" type="email" id="email" value="{{ $user->email }}"
                                class="w-full px-3 py-2 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs sm:text-sm lg:text-md"
                                placeholder="sample@gmail.com" required>
                            @error('email')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <label class="block text-gray-700 text-xs sm:text-sm lg:text-md font-medium mb-1 lg:mb-2"
                                for="password">Password</label>
                            <input name="password" type="text" id="password" value=""
                                class="w-full px-3 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-md focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs sm:text-sm lg:text-md"
                                placeholder="Enter new password (optional)">
                            @error('password')
                                <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end space-y-4 sm:space-y-0 sm:space-x-4">
                            <button type="submit"
                                class="w-full sm:w-auto py-2 px-6 text-xs sm:text-sm font-medium text-white bg-blue-700 rounded-md hover:bg-blue-800 focus:outline-none focus:ring-1 focus:ring-blue-200">
                                Save
                            </button>

                            <button type="button" onclick="window.history.back();"
                                class="w-full sm:w-auto py-2 px-4 text-xs sm:text-sm font-medium text-blue-700 border border-blue-700 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-1 focus:ring-blue-200">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>

            </section>

        </div>
    </div>
</div>

{{-- Footer --}}
@include('super-admin.footer')
