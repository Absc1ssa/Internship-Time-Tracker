{{-- Header --}}
@include('admin.links_css')

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust bg-gray-50">
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 shadow-lg text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl hover:text-blue-200 transition-colors">☰</button>
        <h1 class="text-xl font-semibold tracking-wider">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <div class="max-w-4xl mx-auto p-6">
        <!-- Breadcrumb -->
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('admin.interns') }}"
                        class="flex items-center text-gray-700 hover:text-blue-600 space-x-2">
                        <span class="material-symbols-outlined text-lg">
                            group
                        </span>
                        <span class="text-sm font-medium">Interns</span>
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
                        <span class="ml-2 text-gray-500 font-medium">Edit Intern</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Main Form Card -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-800">Edit Intern Details</h2>
            </div>

            <form action="{{ route('admin.updateIntern', ['id' => $intern->id]) }}" method="POST"
                enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Last Name -->
                <div>
                    <label for="lname" class="block text-sm font-medium text-gray- mb-2">Last Name</label>
                    <input type="text" name="lname" id="lname" value="{{ old('lname', $intern->user->lname) }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('lname') border-red-300 @enderror"
                        required>
                    @error('lname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div>
                    <label for="fname" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" name="fname" id="fname" value="{{ old('fname', $intern->user->fname) }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('fname') border-red-300 @enderror"
                        required>
                    @error('fname')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $intern->user->email) }}"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-300 @enderror"
                        required>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password (Optional)</label>
                    <input type="password" name="password" id="password"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-300 @enderror"
                        placeholder="Enter new password (optional)">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Office -->
                <div>
                    <label for="office_id" class="block text-sm font-medium text-gray-700 mb-2">Office</label>
                    <select name="office_id" id="office_id"
                        class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('office_id') border-red-300 @enderror"
                        required>
                        <option value="" disabled>Select Office</option>
                        @foreach ($offices as $office)
                            <option value="{{ $office->id }}"
                                {{ old('office_id', $intern->office_id) == $office->id ? 'selected' : '' }}>
                                {{ $office->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('office_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Avatar -->
                <div>
                    <label for="avatar" class="block text-sm font-medium text-gray-700">Avatar</label>
                    <div class="mt-1 flex space-x-4 items-start">
                        <!-- Old Avatar -->
                        @if ($intern->avatar)
                            <div class="relative group">
                                <img src="{{ asset('storage/' . $intern->avatar) }}" alt="Current Avatar"
                                    class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                <div
                                    class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                    <span class="text-white text-sm">Current Avatar</span>
                                </div>
                            </div>
                        @endif

                        <!-- New Avatar Preview -->
                        <div id="newAvatarPreviewContainer" class="hidden">
                            <img id="newAvatarPreview" src=""
                                class="h-32 w-32 object-cover rounded-lg border border-gray-300"
                                alt="New Avatar Preview">
                            <p class="text-xs text-gray-500 mt-2">Newly selected avatar</p>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div
                            class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none"
                                    viewBox="0 0 48 48" aria-hidden="true">
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="avatar"
                                        class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                        <span>Upload a new avatar</span>
                                        <input id="avatar" name="avatar" type="file" class="sr-only"
                                            accept="image/*">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                        </div>
                        @error('avatar')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>


                <!-- Action Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('admin.interns') }}"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avatarInput = document.getElementById('avatar');
        const newAvatarPreviewContainer = document.getElementById('newAvatarPreviewContainer');
        const newAvatarPreview = document.getElementById('newAvatarPreview');

        avatarInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    newAvatarPreview.src = e.target.result;
                    newAvatarPreviewContainer.classList.remove('hidden'); // Show new avatar preview
                };
                reader.readAsDataURL(file);
            } else {
                newAvatarPreview.src = '';
                newAvatarPreviewContainer.classList.add('hidden'); // Hide new avatar preview
            }
        });
    });
</script>
