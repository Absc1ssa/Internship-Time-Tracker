@include('head_links')

<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100">
    <!-- Header Background with Back Button -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-700 pt-8 pb-32">
        <div class="container mx-auto px-4">
            <a href="{{ route('dashboard') }}"
                class="inline-flex items-center text-white hover:text-blue-100 transition-colors">
                <span class="material-icons mr-1">arrow_back</span>
                <span class="text-sm md:text-base lg:text-lg font-medium">Back</span>
            </a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 -mt-24">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column: Profile Card -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-xl shadow-lg p-6 md:p-8 lg:p-10 transform transition-all duration-300 hover:shadow-xl">
                    <!-- Avatar Section -->
                    <div class="flex flex-col items-center mb-4">
                        <div class="relative">
                            <img id="avatarPreview" src="{{ $avatar }}" alt="User Avatar"
                                class="w-24 h-24 md:w-24 md:h-24 lg:w-28 lg:h-28 rounded-full border-4 border-white shadow-gray-500 shadow-md object-cover">

                            <form id="avatarForm" action="{{ route('avatar-upload') }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <label for="avatarUpload"
                                    class="absolute bottom-0 right-0 bg-blue-600 p-1 md:p-1 lg:p-1 rounded-full shadow-md cursor-pointer hover:bg-blue-700 transition-colors">
                                    <span
                                        class="material-icons text-white text-sm md:text-base lg:text-lg">photo_camera</span>
                                </label>
                                <input type="file" id="avatarUpload" name="avatar" accept="image/*" class="hidden">
                            </form>
                        </div>


                        <div class="mt-4 text-center">
                            <h1 class="text-lg md:text-lg lg:text-2xl font-bold text-gray-900">
                                {{ auth()->user()->fname }} {{ auth()->user()->lname }}</h1>
                            <p class="text-sm md:text-base lg:text-md text-gray-600 capitalize">
                                {{ auth()->user()->role }}</p>
                        </div>
                    </div>

                    <!-- Contact Details Section -->
                    <div class="space-y-3 md:space-y-4 lg:space-y-5 text-gray-700">
                        <div class="flex items-center gap-2 md:gap-3">
                            <span class="material-icons text-blue-600 text-sm md:text-base lg:text-lg">mail</span>
                            <div class="text-left text-xs md:text-sm lg:text-base">
                                <p>{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 md:gap-3">
                            <span
                                class="material-icons text-blue-600 text-sm md:text-base lg:text-lg">location_on</span>
                            <div class="text-left text-xs md:text-sm lg:text-base">
                                <p>{{ $location }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Attendance Record -->
            <div class="lg:col-span-2 space-y-8">
                <div
                    class="bg-white rounded-xl shadow-lg p-6 md:p-8 lg:p-10 transform transition-all duration-300 hover:shadow-xl">
                    <div class="space-y-4 md:space-y-6 lg:space-y-8">
                        <div class="flex justify-between items-center mb-4 md:mb-6">
                            <h2 class="text-lg md:text-xl lg:text-xl font-semibold text-gray-900">Attendance Record</h2>
                        </div>

                        <!-- AM/PM Toggle -->
                        {{-- <div class="flex justify-center mb-4 md:mb-6">
                            <div class="flex rounded-full overflow-hidden shadow-sm shadow-gray-700">
                                <button id="amButton"
                                    class="py-2 px-6 md:px-8 lg:px-12 text-xs md:text-sm lg:text-base font-semibold {{ request()->routeIs('profile-attendance') ? 'text-white bg-blue-600' : 'text-gray-700 bg-gray-100' }} transition-all duration-300 focus:outline-none">
                                    A.M
                                </button>
                                <a href="{{ route('profile-attendance-pm') }}">
                                    <button id="pmButton"
                                        class="py-2 px-6 md:px-8 lg:px-12 text-xs md:text-sm lg:text-base font-semibold {{ request()->routeIs('profile-attendance') ? 'text-gray-700 bg-gray-100' : 'text-white bg-blue-600' }} transition-all duration-300 focus:outline-none">
                                        P.M
                                    </button>
                                </a>
                            </div>
                        </div> --}}

                        @if ($attendance->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table
                                    class="min-w-full divide-y divide-gray-200 text-xs md:text-sm lg:text-base border border-gray-300">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="text-center border px-4 py-2 font-semibold text-gray-700">Date
                                            </th>
                                            <th class="text-center border px-4 py-2 font-semibold text-blue-600">AM
                                                Clock In</th>
                                            <th class="text-center border px-4 py-2 font-semibold text-blue-600">AM
                                                Clock Out</th>
                                            <th class="text-center border px-4 py-2 font-semibold text-blue-600">PM
                                                Clock In</th>
                                            <th class="text-center border px-4 py-2 font-semibold text-blue-600">PM
                                                Clock Out</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach ($attendance as $attend)
                                            <tr>
                                                <td class="px-4 py-2 text-center">{{ $attend->date }}</td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $attend->am_clock_in ? \Carbon\Carbon::parse($attend->am_clock_in)->format('h:i A') : '—' }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $attend->am_clock_out ? \Carbon\Carbon::parse($attend->am_clock_out)->format('h:i A') : '—' }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $attend->pm_clock_in ? \Carbon\Carbon::parse($attend->pm_clock_in)->format('h:i A') : '—' }}
                                                </td>
                                                <td class="px-4 py-2 text-center">
                                                    {{ $attend->pm_clock_out ? \Carbon\Carbon::parse($attend->pm_clock_out)->format('h:i A') : '—' }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <p class="text-red-600 font-semibold">No attendance records found.</p>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('toggle_footer')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const avatarUpload = document.getElementById('avatarUpload');
        const avatarForm = document.getElementById('avatarForm');
        const avatarPreview = document.getElementById('avatarPreview');

        avatarUpload.addEventListener('change', async (event) => {
            const formData = new FormData(avatarForm);

            try {
                const response = await fetch(avatarForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')
                            .value,
                    },
                });

                if (response.ok) {
                    const data = await response.json();
                    avatarPreview.src = data.avatar; // Update the avatar preview
                    alert(data.message); // Notify the user
                } else {
                    const error = await response.json();
                    alert(error.message || 'Failed to upload avatar.');
                }
            } catch (error) {
                console.error('Error uploading avatar:', error);
                alert('An error occurred while uploading the avatar.');
            }
        });
    });
</script>
