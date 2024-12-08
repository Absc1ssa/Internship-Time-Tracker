<!-- Delete Intern Modal -->
<div id="deleteInternModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
        <div class="flex justify-center items-center mx-auto h-10 w-10 bg-red-100 rounded-full mb-2">
            <div class="flex justify-center items-center h-7 w-7 bg-red-300 rounded-full">
                <p class="text-red-800 text-xl font-bold">!</p>
            </div>
        </div>
        <!-- Modal Header -->
        <h2 class="text-lg font-semibold text-gray-900 mb-4 text-center">Delete Intern</h2>
        <p class="text-gray-600 text-sm text-center mb-6">
            Are you sure you want to delete <span id="internName" class="font-semibold"></span>?
            This action cannot be undone.
        </p>
        <div class="flex justify-end space-x-4">
            <button id="cancelDeleteModal" class="bg-gray-300 hover:bg-gray-400 text-xs px-4 py-2 rounded-sm">
                Cancel
            </button>
            <form id="deleteInternForm" method="POST" action="">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 hover:bg-red-600 text-xs text-white px-4 py-2 rounded-sm">
                    Delete
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('deleteInternModal');
        const cancelBtn = document.getElementById('cancelDeleteModal');
        const internNameElem = document.getElementById('internName');
        const deleteForm = document.getElementById('deleteInternForm');
        const deleteButtons = document.querySelectorAll('.delete-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', () => {
                const internId = button.getAttribute('data-intern-id');
                const internName = button.getAttribute('data-intern-name');
                internNameElem.textContent = internName;
                deleteForm.action = `/admin/interns/${internId}`;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        cancelBtn.addEventListener('click', () => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        });

        window.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        });
    });
</script>
