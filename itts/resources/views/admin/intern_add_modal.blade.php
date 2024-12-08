<!-- Add Intern Modal -->
<div id="addInternModal" class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }} flex items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-sm shadow-lg w-full max-w-lg p-6 sm:p-8 mx-4 sm:mx-0">
        <!-- Modal Header -->
        <div class="flex justify-between items-center mb-3">
            <h4 class="text-xl font-semibold text-gray-800">Add Intern</h4>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <span class="material-icons">close</span>
            </button>
        </div>

        <!-- Modal Body -->
        <form method="POST" action="{{ route('admin.addIntern') }}" enctype="multipart/form-data">
            @csrf
            <!-- Last Name and First Name -->
            <div class="sm:flex sm:space-x-4 mb-2">
                <div class="w-full sm:w-1/2 mb-2 sm:mb-0">
                    <label for="lname" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input 
                        name="lname" 
                        type="text" 
                        id="lname" 
                        value="{{ old('lname') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light @error('lname') border-red-500 @enderror" 
                        placeholder="Enter last name" 
                        required>
                    @error('lname')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="w-full sm:w-1/2">
                    <label for="fname" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input 
                        name="fname" 
                        type="text" 
                        id="fname" 
                        value="{{ old('fname') }}" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light @error('fname') border-red-500 @enderror" 
                        placeholder="Enter first name" 
                        required>
                    @error('fname')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Email -->
            <div class="mb-2">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    name="email" 
                    type="email" 
                    id="email" 
                    value="{{ old('email') }}" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light @error('email') border-red-500 @enderror" 
                    placeholder="sample@gmail.com" 
                    required>
                @error('email')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-2">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                <input 
                    name="password" 
                    type="password" 
                    id="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs font-light @error('password') border-red-500 @enderror" 
                    placeholder="Enter password" 
                    required>
                @error('password')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Office -->
            <div class="mb-2">
                <label for="office_id" class="block text-sm font-medium text-gray-700 mb-2">Office</label>
                <select 
                    name="office_id" 
                    id="office_id" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-sm focus:outline-none focus:ring-1 focus:ring-blue-400 text-xs @error('office_id') border-red-500 @enderror" 
                    required>
                    <option value="" disabled {{ old('office_id') ? '' : 'selected' }}>Choose an office...</option>
                    @foreach ($offices as $office)
                        <option value="{{ $office->id }}" {{ old('office_id') == $office->id ? 'selected' : '' }}>
                            {{ $office->name }}
                        </option>
                    @endforeach
                </select>
                @error('office_id')
                    <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Avatar -->
            <div class="mb-6">
                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Upload Avatar</label>
                <div class="relative">
                    <input name="avatar" type="file" id="avatar" class="hidden">
                    <label for="avatar"
                        class="flex items-center justify-center cursor-pointer px-4 py-2 bg-gray-100 border border-gray-300 rounded-sm hover:bg-gray-200 transition">
                        <span class="material-icons text-blue-500 mr-2">attach_file</span>
                        <span class="text-gray-700">Upload photo here</span>
                    </label>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end space-x-3">
                <button type="button" id="closeModalFooter" class="bg-gray-300 text-black hover:bg-gray-400 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white hover:bg-blue-700 text-xs h-full px-4 py-2 rounded-sm cursor-pointer transition">Save</button>
            </div>
        </form>
    </div>
</div>





{{-- Script for Modal Functionality --}}
<script>
    const openModalButton = document.getElementById('openInternModal');
    const closeModalButtons = document.querySelectorAll('#closeModal, #closeModalFooter');
    const modal = document.getElementById('addInternModal');

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
</script>
