{{-- Header - Links - Css --}}
@include('admin.links_css')

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust">
    <!-- Fixed Header -->
    <header class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">
        <h1 class="text-xl font-semibold">Offices</h1>
        <button id="openModal" class="flex items-center my-3 px-3 py-1 h-10 bg-blue-500 text-white rounded-md hover:bg-blue-600">
            <span class="material-icons mr-2">add</span>
            <span class="text-sm font-medium">Add Office</span>
        </button>

        <!-- Responsive Cards Container -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 py-3">
            @foreach ($offices as $office)
                <div class="bg-white p-4 rounded-lg shadow-md shadow-gray-400 relative">
                    <!-- Card Title (Clickable) -->
                    <div class="flex items-center mb-3 cursor-pointer" data-id="{{ $office->id }}">
                        <span class="material-icons text-blue-500 mr-2">location_on</span>
                        <h2 class="text-lg font-semibold uppercase">{{ $office->name }}</h2>
                    </div>

                    <!-- Office Image -->
                    <img src="{{ $office->photo ? asset('storage/' . $office->photo) : asset('images/default-office.png') }}"
                        alt="{{ $office->name }}" class="w-full h-32 object-cover mb-3 border border-blue-700">

                    <!-- Hover Effect: Interns Assigned -->
                    <div class="absolute top-0 left-0 w-full h-full rounded-md bg-gray-900 bg-opacity-90 opacity-0 transition-opacity duration-300 p-4 z-1 office-hover-{{ $office->id }} pointer-events-none">
                        <!-- Close Button -->
                        <button class="absolute top-2 right-2 text-white p-2 "
                            data-id="{{ $office->id }}" aria-label="Close Intern List">
                            <span class="material-icons text-sm">close</span>
                        </button>

                        <p class="text-white text-md font-semibold mb-2">Assigned Interns</p>
                        <ul class="space-y-2 text-white text-sm overflow-y-auto">
                            @forelse ($office->interns as $intern)
                                <li class="flex items-center space-x-2">
                                    <img src="{{ $intern->avatar ? asset('storage/' . $intern->avatar) : asset('images/default-avatar.png') }}"
                                        alt="{{ $intern->fname }} {{ $intern->lname }}" class="w-5 h-5 rounded-full">
                                    <span class="text-white text-sm">{{ $intern->user->fname }} {{ $intern->user->lname }}</span>
                                </li>
                            @empty
                                <li class="text-white text-sm">No interns assigned</li>
                            @endforelse
                        </ul>
                    </div>

                    <!-- Office Location -->
                    <p class="text-sm text-gray-600 mb-4 cursor-pointer" data-id="{{ $office->id }}">{{ $office->location }}</p>

                    <!-- Action Buttons -->
                    <div class="flex space-x-2 z-20 relative">
                        <a href="{{ route('admin.office_edit', ['id' => $office->id]) }}"
                            class="flex items-center justify-center bg-blue-500 text-white px-3 py-1 rounded-sm hover:bg-blue-600">
                            <span class="material-icons text-sm mr-1 hidden sm:inline-block">edit</span>
                            <span class="text-sm">Edit</span>
                        </a>

                        <button id="openDeleteModal"
                            class="flex items-center justify-center bg-red-500 text-white px-3 py-1 rounded-sm hover:bg-red-600"
                            data-office-id="{{ $office->id }}">
                            <span class="material-icons text-sm mr-1 hidden sm:inline-block">delete</span>
                            <span class="text-sm">Delete</span>
                        </button>
                    </div>
                </div>

                <style>
                    .office-hover-{{ $office->id }} {
                        pointer-events: none;
                        /* Prevent interaction by default */
                    }
                
                    .office-hover-{{ $office->id }}.pointer-events-auto {
                        pointer-events: auto;
                        /* Allow interaction once it's visible */
                    }
                </style>
            @endforeach
        </div>
    </section>
</div>

{{-- Add Office Modal --}}
@include('admin.office_add')

{{-- Delete Office Modal --}}
@include('admin.office_delete')

@include('admin.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Select all clickable elements (office name and location)
        const clickableElements = document.querySelectorAll('[data-id]');

        clickableElements.forEach(element => {
            element.addEventListener('click', function() {
                const officeId = this.getAttribute('data-id');
                const hoverElement = document.querySelector('.office-hover-' + officeId);

                // Toggle the opacity and pointer events of the hover element
                if (hoverElement) {
                    hoverElement.classList.toggle('opacity-100'); // Toggle visibility
                    hoverElement.classList.toggle('pointer-events-auto'); // Enable interaction with the hover content
                }
            });
        });

        // Close button functionality
        const closeButtons = document.querySelectorAll('[aria-label="Close Intern List"]');

        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const officeId = this.getAttribute('data-id');
                const hoverElement = document.querySelector('.office-hover-' + officeId);

                // Hide the hover effect when the close button is clicked
                if (hoverElement) {
                    hoverElement.classList.remove('opacity-100');
                    hoverElement.classList.remove('pointer-events-auto');
                }
            });
        });
    });
</script>

