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
    <div class=""> <!-- Added padding-top to account for fixed header -->
        <div class="max-w-4xl mx-auto p-6">
            <!-- Enhanced Breadcrumb -->
            <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('admin.offices') }}"
                            class="flex items-center text-gray-700 hover:text-blue-600 space-x-2">
                            <!-- Office Icon -->
                            <span class="material-symbols-outlined text-lg">
                                domain
                            </span>
                            <span class="text-sm font-medium">Offices</span>
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
                            <span class="ml-2 text-gray-500 font-medium">Edit Office</span>
                        </div>
                    </li>
                </ol>
            </nav>


            <!-- Main Form Card -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h2 class="text-2xl font-bold text-gray-800">Edit Office Details</h2>
                    <p class="mt-1 text-sm text-gray-600">Update the information for this office location.</p>
                </div>

                <form action="{{ route('admin.updateOffice', ['id' => $office->id]) }}" method="POST"
                    enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <!-- Office Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Office Name</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="name" id="name" value="{{ old('name', $office->name) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                                required>
                        </div>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Office Location -->
                    <div class="relative">
                        <label for="location" class="block text-sm font-medium text-gray-700">Office Location</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <input type="text" name="location" id="location"
                                value="{{ old('location', $office->location) }}"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-300 @enderror"
                                placeholder="Enter location" autocomplete="off" required>
                        </div>
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <ul id="locationSuggestions"
                            class="absolute bg-white border border-gray-300 rounded-sm shadow-md mt-1 z-10 hidden max-h-40 overflow-y-auto w-full">
                        </ul>
                    </div>


                    <!-- Office Photo -->
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700">Office Photo</label>
                        <div class="mt-1 flex space-x-4 items-start">
                            <!-- Old Photo -->
                            @if ($office->photo)
                                <div class="relative group">
                                    <img src="{{ asset('storage/' . $office->photo) }}" alt="Current Office Photo"
                                        class="h-32 w-32 object-cover rounded-lg border border-gray-300">
                                    <div
                                        class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-200 rounded-lg flex items-center justify-center">
                                        <span class="text-white text-sm">Current Photo</span>
                                    </div>
                                </div>
                            @endif

                            <!-- New Photo Preview -->
                            <div id="newPhotoPreviewContainer" class="hidden">
                                <img id="newPhotoPreview" src=""
                                    class="h-32 w-32 object-cover rounded-lg border border-gray-300"
                                    alt="New Photo Preview">
                                <p class="text-xs text-gray-500 mt-2">Newly selected photo</p>
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
                                        <label for="photo"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                                            <span>Upload a new photo</span>
                                            <input id="photo" name="photo" type="file" class="sr-only"
                                                accept="image/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                                </div>
                            </div>
                            @error('photo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>


                    <!-- Action Buttons -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                        <button type="button" onclick="handleCancel()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-blue-500">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@include('admin.footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Elements for photo input and preview
        const photoInput = document.getElementById('photo');
        const newPhotoPreviewContainer = document.getElementById('newPhotoPreviewContainer');
        const newPhotoPreview = document.getElementById('newPhotoPreview');

        // Elements for location autocomplete
        const locationInput = document.getElementById('location');
        const suggestionsList = document.getElementById('locationSuggestions');

        // Handle photo preview
        photoInput.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    newPhotoPreview.src = e.target.result;
                    newPhotoPreviewContainer.classList.remove('hidden'); // Show new photo preview
                };
                reader.readAsDataURL(file);
            } else {
                newPhotoPreview.src = '';
                newPhotoPreviewContainer.classList.add('hidden'); // Hide new photo preview
            }
        });

        // Handle location suggestions
        locationInput.addEventListener('input', async function () {
            const query = locationInput.value.trim();

            // Hide suggestions if input is too short
            if (query.length < 3) {
                suggestionsList.innerHTML = '';
                suggestionsList.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5&countrycodes=PH`
                );

                if (!response.ok) {
                    console.error('Failed to fetch location suggestions');
                    return;
                }

                const results = await response.json();

                // Clear previous suggestions
                suggestionsList.innerHTML = '';
                suggestionsList.classList.remove('hidden');

                if (results.length === 0) {
                    const noResult = document.createElement('li');
                    noResult.textContent = 'No results found';
                    noResult.classList.add('px-4', 'py-2', 'text-gray-500', 'italic');
                    suggestionsList.appendChild(noResult);
                    return;
                }

                // Display new suggestions
                results.forEach(location => {
                    const listItem = document.createElement('li');
                    listItem.textContent = location.display_name;
                    listItem.classList.add('px-4', 'py-2', 'hover:bg-gray-200', 'cursor-pointer');
                    listItem.addEventListener('click', () => {
                        locationInput.value = location.display_name;
                        suggestionsList.classList.add('hidden');
                    });
                    suggestionsList.appendChild(listItem);
                });
            } catch (error) {
                console.error('Error fetching location suggestions:', error);
            }
        });

        // Hide suggestions when clicking outside
        document.addEventListener('click', (event) => {
            if (!locationInput.contains(event.target) && !suggestionsList.contains(event.target)) {
                suggestionsList.classList.add('hidden');
            }
        });

        // Handle cancel button
        const cancelButton = document.querySelector('button[onclick="handleCancel()"]');
        cancelButton.addEventListener('click', () => {
            window.location.href = "{{ route('admin.offices') }}";
        });
    });
</script>

