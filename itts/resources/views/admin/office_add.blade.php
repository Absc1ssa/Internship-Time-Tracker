<!-- Add Office Modal -->
<div id="addOfficeModal"
    class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-sm shadow-lg w-full max-w-lg p-6 sm:p-8 mx-4 sm:mx-0">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-6">
            <h4 class="text-xl font-semibold text-gray-800">Add Office</h4>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <span class="material-icons">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form id="addOfficeForm" action="{{ route('admin.addOffice') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- Office Name -->
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="office-name">Office Name</label>
                <input type="text" id="office-name" name="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm font-light"
                    placeholder="Enter office name" required>
                @error('name')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Office Photo -->
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="office-photo">Office Photo</label>
                <div class="relative">
                    <input type="file" id="office-photo" name="photo" class="hidden" accept="image/*">
                    <label for="office-photo"
                        class="flex items-center justify-center cursor-pointer px-4 py-2 bg-gray-100 border border-gray-300 rounded-sm hover:bg-gray-200 transition">
                        <span class="material-icons text-blue-500 mr-2">attach_file</span>
                        <span class="text-gray-700">Attach Office Photo</span>
                    </label>
                    <img id="photo-preview" src=""
                        class="w-full h-32 object-cover mt-3 hidden border border-gray-300 rounded-sm">
                </div>
                @error('photo')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Office Location -->
            <div class="mb-2 relative">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="search_input">Office Location</label>
                <input type="text" id="search_input" name="location"
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-sm"
                    placeholder="Enter location" required>
                <ul id="suggestions"
                    class="absolute border border-gray-300 rounded-sm bg-white shadow-md mt-1 z-10 hidden max-h-40 overflow-y-auto w-full">
                </ul>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3">
                <button type="button" id="closeModalFooter"
                    class="bg-gray-300 text-black hover:bg-gray-400 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Cancel</button>
                <button type="submit"
                    class="bg-blue-500 text-white rounded-md hover:bg-blue-700 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Save</button>
            </div>
        </form>
    </div>
</div>


<!-- JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openModalButton = document.getElementById('openModal');
        const closeModalButtons = document.querySelectorAll('#closeModal, #closeModalFooter');
        const modal = document.getElementById('addOfficeModal');
        const searchInput = document.getElementById('search_input');
        const suggestionsList = document.getElementById('suggestions');
        const photoInput = document.getElementById('office-photo');
        const photoPreview = document.getElementById('photo-preview');


        // Open the modal
        openModalButton.addEventListener('click', () => {
            modal.classList.remove('hidden');
        });

        // Close the modal
        closeModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });

        // Hide modal after successful submission
        const addOfficeForm = document.getElementById('addOfficeForm');
        addOfficeForm.addEventListener('submit', () => {
            modal.classList.add('hidden');
        });

        // Open modal
        if (openModalButton) {
            openModalButton.addEventListener('click', () => {
                modal.classList.remove('hidden');
            });
        }

        // Cancel button functionality
        closeModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Reset form fields
                const form = document.getElementById('addOfficeForm');
                form.reset();

                // Reset photo preview
                photoPreview.src = '';
                photoPreview.classList.add('hidden');

                // Hide modal
                modal.classList.add('hidden');

                // Redirect to offices.blade
                // window.location.href = "{{ route('admin.offices') }}";
            });
        });

        // Photo Preview
        photoInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    photoPreview.src = e.target.result;
                    photoPreview.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            } else {
                photoPreview.src = '';
                photoPreview.classList.add('hidden');
            }
        });

        // Fetch suggestions for location input
        searchInput.addEventListener('input', async function() {
            const query = searchInput.value.trim();
            if (query.length < 3) {
                suggestionsList.innerHTML = '';
                suggestionsList.classList.add('hidden');
                return;
            }
            await fetchSuggestions(query);
        });

        async function fetchSuggestions(query) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?q=${encodeURIComponent(query)}&format=json&addressdetails=1&limit=5&countrycodes=PH`
                );
                const results = await response.json();
                displaySuggestions(results);
            } catch (error) {
                console.error('Error fetching location suggestions:', error);
            }
        }

        function displaySuggestions(locations) {
            suggestionsList.innerHTML = '';
            suggestionsList.classList.remove('hidden');

            locations.forEach(location => {
                const listItem = document.createElement('li');
                listItem.textContent = location.display_name;
                listItem.classList.add('px-4', 'py-2', 'hover:bg-gray-200', 'cursor-pointer');
                listItem.addEventListener('click', () => {
                    searchInput.value = location.display_name;
                    suggestionsList.classList.add('hidden');
                });
                suggestionsList.appendChild(listItem);
            });
        }

        // Hide suggestions when clicking outside
        document.addEventListener('click', (event) => {
            if (!searchInput.contains(event.target) && !suggestionsList.contains(event.target)) {
                suggestionsList.classList.add('hidden');
            }
        });

    });
</script>
