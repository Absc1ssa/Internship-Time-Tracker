{{-- Add INtern Modal --}}
<div id="addUserModal" class="fixed inset-0 z-50 {{ $errors->any() ? 'flex' : 'hidden' }} flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-sm shadow-lg w-full max-w-lg p-6 sm:p-8 mx-4 sm:mx-0">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-xl font-semibold text-gray-800">Add Admin</h4>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <span class="material-icons">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form method="POST" action="{{ route('super-admin.addAdmin') }}">
            @csrf

            <!-- Last Name and First Name Grouped -->
            <div class="sm:flex sm:space-x-4 mb-2">
                <!-- Last Name -->
                <div class="w-full sm:w-1/2 mb-2 sm:mb-0">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="last-name">Last Name</label>
                    <input name="lname" type="text" id="last-name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light"
                        placeholder="Enter last name" value="{{ old('lname') }}" required>
                    @error('lname')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- First Name -->
                <div class="w-full sm:w-1/2">
                    <label class="block text-gray-700 text-sm font-medium mb-2" for="first-name">First Name</label>
                    <input name="fname" type="text" id="first-name"
                        class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light"
                        placeholder="Enter first name" value="{{ old('fname') }}" required>
                    @error('fname')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="office-name">Email</label>
                <input name="email" type="email" id="email"
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light"
                    placeholder="sample@gmail.com" value="{{ old('email') }}" required>
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-2">
                <label class="block text-gray-700 text-sm font-medium mb-2" for="office-name">Password</label>
                <input name="password" type="text" id="password"
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light"
                    placeholder="Enter password" required>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3">
                <button type="button" id="closeModalFooter"
                    class="bg-gray-300 text-black hover:bg-gray-400 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Cancel</button>
                <button type="submit"
                    class="bg-blue-500 text-white hover:bg-blue-700 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Save</button>
            </div>
        </form>
    </div>
</div>


{{-- Script for Modal Functionality --}}
<script>
    const openModalButton = document.getElementById('openUsersModal');
    const closeModalButtons = document.querySelectorAll('#closeModal, #closeModalFooter');
    const modal = document.getElementById('addUserModal');

    // Open modal
    openModalButton.addEventListener('click', () => {
        modal.classList.remove('hidden');
    });

    // Close modal
    closeModalButtons.forEach(button => {
        button.addEventListener('click', () => {
            modal.classList.add('hidden');
        });
    });

    // Close modal by clicking outside the modal
    window.addEventListener('click', (event) => {
        if (event.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Close modal with 'Esc' key
    window.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            modal.classList.add('hidden');
        }
    });

    document.querySelector('#addUserModal form').addEventListener('submit', () => {
        modal.classList.add('hidden');
    });
</script>
