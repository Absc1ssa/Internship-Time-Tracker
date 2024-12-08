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
    <section class="p-6 ">
        <h1 class="text-xl font-semibold">Admins</h1>

        <div class="flex items-center">
            {{-- Add User Button --}}
            <button id="openUsersModal"
                class="flex items-center my-3 px-3 py-1 h-10 bg-blue-500 text-white rounded-sm hover:bg-blue-600">
                <span class="material-icons mr-2">add</span>
                <span class="text-sm">Add Admin</span>
            </button>
        </div>

        <!-- User Table -->
        <div class="h-auto bg-white rounded-sm shadow-gray-400 shadow-sm my-4 p-4 overflow-x-auto">
            <!-- Responsive Table -->
            <table class="min-w-full bg-white text-left">
                <thead>
                    <tr class="bg-[#FFE047]">
                        <th class="px-4 py-2 text-sm text-gray-900">Full Name</th>
                        <th class="px-4 py-2 text-sm text-gray-900">Email</th>
                        <th class="px-4 py-2 text-sm text-gray-900">Role</th>
                        <th class="px-4 py-2 text-sm text-gray-900">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td class="border-t px-4 py-2 text-sm capitalize">{{ $user->fname }} {{ $user->lname }}</td>
                            <td class="border-t px-4 py-2 text-sm ">{{ $user->email }}</td>
                            <td class="border-t px-4 py-2 text-sm">
                                <span class="inline-block px-3 py-1 rounded-full font-medium uppercase
                                    {{ $user->role === 'super_admin' ? 'bg-blue-100 text-blue-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            
                            <td class="border-t px-4 py-2 text-sm">
                                <div class="flex space-x-2">
                                    <!-- Edit Button -->
                                    <a href="{{ route('super-admin.users_edit', ['id' => $user->id]) }}"
                                        class="text-blue-500 hover:text-blue-700 flex items-center">
                                        <span class="material-symbols-outlined">edit</span>
                                    </a>


                                    <!-- Delete Button -->
                                    <button class="text-red-500 hover:text-red-700 delete-user-btn"
                                        data-user-id="{{ $user->id }}">
                                        <span class="material-symbols-outlined">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $users->links() }}
            </div>

        </div>
    </section>
</div>

{{-- Footer --}}
@include('super-admin.footer')

{{-- Modals --}}
@include('super-admin.users_add')
@include('super-admin.users_delete')
