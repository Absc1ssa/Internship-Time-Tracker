<!-- Delete User Modal -->
<div id="deleteUsersModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-sm shadow-lg w-full max-w-lg p-6 sm:p-8 mx-4 sm:mx-0">
        <div class="flex justify-center items-center mx-auto h-10 w-10 bg-red-100 rounded-3xl">
            <div class="flex justify-center items-center h-7 w-7 bg-red-300 rounded-3xl">
                <p class="text-red-800 text-xl font-bold">!</p>
            </div>
        </div>
        <h4 class="text-xl font-semibold text-center text-gray-800 mb-4">Delete User</h4>
        <p class="text-gray-600 mb-6 text-sm text-center">Are you sure you want to delete this user?</p>
        <div class="flex justify-end space-x-4">
            <!-- Cancel Button -->
            <button id="cancelModal" class="bg-gray-300 hover:bg-gray-400 text-black text-xs h-full px-4 py-2 rounded-sm transition">
                Cancel
            </button>
            <!-- Delete Form -->
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-xs h-full text-white px-4 py-2 rounded-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        const deleteModal = document.getElementById('deleteUsersModal');
        const deleteButtons = document.querySelectorAll('.delete-user-btn');
        const deleteForm = document.getElementById('deleteForm');
        const cancelModalButton = document.getElementById('cancelModal');

        // Open modal with the correct user ID
        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const userId = button.getAttribute('data-user-id');
                deleteForm.action = `/super-admin/delete-user/${userId}`;
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
            });
        });

        // Close modal function
        const closeModal = () => {
            deleteModal.classList.add('hidden');
            deleteModal.classList.remove('flex');
        };

        // Close modal when cancel button is clicked
        cancelModalButton.addEventListener('click', closeModal);

        // Close modal by clicking outside of modal content
        window.addEventListener('click', (event) => {
            if (event.target === deleteModal) {
                closeModal();
            }
        });

        // Close modal with 'Esc' key
        window.addEventListener('keydown', (event) => {
            if (event.key === 'Escape') {
                closeModal();
            }
        });
    });
</script>
