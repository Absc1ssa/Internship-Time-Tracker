{{-- head and cdn links --}}
@include('head_links')

{{-- navbar --}}
@include ('navbar')

<!-- Main Content Wrapper with padding to prevent navbar overlap -->
<div class="pt-20 md:pt-24 min-h-screen">

    <!-- Centered Table Wrapper with fixed width and centered alignment -->
    <div class="flex justify-center">
        <div class="bg-transparent rounded-sm shadow-gray-500 shadow-lg p-6 w-full max-w-2xl"> 

            <!-- Centered AM/PM Toggle Button inside the table wrapper -->
            <div class="flex justify-center mb-6">
                <div id="toggleButton" class="flex rounded-full overflow-hidden shadow-md border border-gray-300">
                    <button id="amButton" 
                        class="py-2 px-8 md:px-12 text-xs md:text-sm font-semibold {{ request()->routeIs('attendance') ? 'text-white bg-blue-600' : 'text-gray-700 bg-gray-200' }} transition-all duration-300 focus:outline-none">
                        A.M
                    </button>
                    <a href="{{ route('pm_attendance') }}">
                        <button id="pmButton"
                            class="py-2 px-8 md:px-12 text-xs md:text-sm font-semibold {{ request()->routeIs('attendance') ? 'text-gray-700 bg-gray-200' : 'text-white bg-blue-600' }} transition-all duration-300 focus:outline-none">
                            P.M
                        </button>
                    </a>
                </div>
            </div>


            

            <!-- Responsive Table with centered alignment and improved padding -->
            <div class="overflow-x-auto">
                <table class="min-w-full w-full bg-white text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-gray-700 border-b">
                            <th class="px-4 py-3 text-sm font-medium">Date</th>
                            <th class="px-4 py-3 text-sm font-medium">Time In</th>
                            <th class="px-4 py-3 text-sm font-medium">Time Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-b hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-sm text-gray-800">01 - Jan - 2024</td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                <div class="flex items-center space-x-2">
                                    <span class="material-symbols-outlined text-green-500 text-base">call_received</span>
                                    <span>8:00 AM</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-800">
                                <div class="flex items-center space-x-2">
                                    <span class="material-symbols-outlined text-red-500 text-base">call_made</span>
                                    <span>11:55 AM</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- toggle button and footer --}}
@include('toggle_footer')
