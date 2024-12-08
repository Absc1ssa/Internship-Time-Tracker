<!-- Delete Confirmation Modal -->
<div id="deleteOfficeModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex justify-center items-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-11/12 sm:w-1/3">
        <div class="flex justify-center items-center mx-auto h-10 w-10 bg-red-100 rounded-full">
            <div class="flex justify-center items-center h-7 w-7 bg-red-300 rounded-full">
                <p class="text-red-800 text-xl font-bold">!</p>
            </div>
        </div>
        <h2 class="text-xl font-semibold text-center text-gray-800 mt-4 mb-4">Delete Office</h2>
        <p class="text-gray-600 mb-6 text-sm text-center">Are you sure you want to delete this office?</p>
        <div class="flex justify-end space-x-4">
            <!-- Cancel Button -->
            <button id="cancelDeleteModal"
                class="bg-gray-300 hover:bg-gray-400 text-black text-xs h-full px-4 py-2 rounded-sm cursor-pointer">Cancel</button>

            <!-- Form Submit Button -->
            <form method="POST" id="deleteOfficeForm">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="bg-red-500 hover:bg-red-600 text-xs h-full text-white px-4 py-2 rounded-sm">Delete</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const deleteOfficeModal = document.getElementById('deleteOfficeModal');
        const openDeleteModalButtons = document.querySelectorAll('#openDeleteModal');
        const cancelDeleteModal = document.getElementById('cancelDeleteModal');
        const deleteOfficeForm = document.getElementById('deleteOfficeForm');

        // Open modal
        openDeleteModalButtons.forEach(button => {
            button.addEventListener('click', () => {
                const officeId = button.getAttribute('data-office-id');
                deleteOfficeForm.action = `/admin/offices/${officeId}`;
                deleteOfficeModal.classList.remove('hidden');
            });
        });

        // Close modal
        cancelDeleteModal.addEventListener('click', () => {
            deleteOfficeModal.classList.add('hidden');
        });

        // Close modal by clicking outside the modal
        window.addEventListener('click', (event) => {
            if (event.target === deleteOfficeModal) {
                deleteOfficeModal.classList.add('hidden');
            }
        });

        // Close modal with 'Esc' key
        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                deleteOfficeModal.classList.add('hidden');
            }
        });
    });
</script>
