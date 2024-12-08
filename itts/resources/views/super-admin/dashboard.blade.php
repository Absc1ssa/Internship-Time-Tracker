{{-- Header --}}
@include('super-admin.header_links')

{{-- Sidebar --}}
@include('super-admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust">
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 shadow-sm shadow-gray-600 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Super Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">
        <h1 class="text-xl font-semibold">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">

            {{-- Super Admin Card --}}
            {{-- <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-auto">
                <div class="flex justify-between items-center p-2 w-full">
                    <div class="flex flex-col">
                        <span class="font-medium text-sm">Super Admin</span>
                        <p class="font-semibold text-3xl">{{ $superAdminCount }}</p>
                    </div>
                    <div>
                        <span class="material-icons text-2xl bg-cover h-full w-10 text-center rounded-sm bg-blue-500 text-white">supervisor_account</span>
                    </div>
                </div>
            </div> --}}

            {{-- Admins Card --}}
            {{-- Combined Admins Card --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-auto">
                <div class="flex justify-between items-center p-2 w-full">
                    <div class="flex flex-col">
                        <span class="font-medium text-sm">Total Admins</span>
                        <p class="font-semibold text-3xl">{{ $totalAdmins }}</p>
                    </div>
                    <div>
                        <span
                            class="material-icons text-2xl bg-cover h-full w-10 text-center rounded-sm bg-blue-500 text-white">supervisor_account</span>
                    </div>
                </div>
            </div>

            {{-- Interns Card --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-auto">
                <div class="flex justify-between items-center p-2 w-full">
                    <div class="flex flex-col">
                        <span class="font-medium text-sm">Total Interns</span>
                        <p class="font-semibold text-3xl">{{ $internCount }}</p>
                    </div>
                    <div>
                        <span
                            class="material-icons text-2xl bg-cover h-full w-10 text-center rounded-sm bg-green-600 text-white">person</span>
                    </div>
                </div>
            </div>

            {{-- Date n Time --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-full ">
                <div class="flex flex-col">
                    <span id="current-date" class="font-medium text-sm mt-2 mx-2">--:--:--</span>
                    <p id="real-time-clock" {{-- style="-webkit-text-stroke: 0.5px #fff; text-shadow: 1px 1px 4px rgba(104, 103, 103, 0.6);" --}}
                        class="tracking-wider text-black font-semibold text-3xl text-center">--:--:--</p>
                </div>
            </div>

        </div>

        <!-- Single Data Bar Graph -->
        <div id="adminChart" class="h-auto w-auto bg-white my-4 p-4 rounded-sm shadow-gray-700 shadow-sm">
            <h2 class="text-center font-semibold text-lg mb-3">Total Number of Users by Role</h2>
            <div id="chart_div" class="w-auto h-auto"></div>
        </div>

    </section>
</div>

{{-- Footer --}}
@include('super-admin.footer')

<!-- Load Google Charts Library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script>
    function updateDateAndClock() {
        const dateElement = document.getElementById('current-date');
        const clockElement = document.getElementById('real-time-clock');
        const now = new Date();

        // Format the date as "Monday, 01 - Jan - 2024"
        const dateOptions = {
            weekday: 'long',
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        };
        const formattedDate = now.toLocaleDateString('en-GB', dateOptions);
        dateElement.textContent = formattedDate;

        // Format the time as HH:MM:SS AM/PM
        let hours = now.getHours();
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        const ampm = hours >= 12 ? 'PM' : 'AM'; // Determine AM or PM

        // Convert hours to 12-hour format
        hours = hours % 12 || 12; // Convert 0 to 12 for 12-hour format
        const timeString = `${String(hours).padStart(2, '0')}:${minutes}:${seconds} ${ampm}`;

        // Update the clock element's text
        clockElement.textContent = timeString;
    }

    // Update the date and clock every second
    setInterval(updateDateAndClock, 1000);

    // Initialize the clock immediately on page load
    updateDateAndClock();
</script>

<script>
    // Load the Google Charts library
    google.charts.load('current', {
        packages: ['corechart']
    });
    google.charts.setOnLoadCallback(drawCharts);

    function drawCharts() {
        drawColumnChart();
    }

    function drawColumnChart() {
        // Create the chart data
        const data = google.visualization.arrayToDataTable([
            ['Category', 'Count', {
                role: 'style'
            }],
            ['Super Admin', {{ $superAdminCount }}, '#4169e1'], // Blue
            ['Admins', {{ $adminCount }}, '#fed000'], // Yellow
            ['Interns', {{ $internCount }}, '#2ca02c'] // Green
        ]);

        // Set the chart options
        const options = {
            title: '',
            titleTextStyle: {
                fontSize: 16,
                bold: true
            },
            chartArea: {
                width: '70%',
                height: '70%'
            },
            legend: {
                position: 'none'
            },
            animation: {
                startup: true,
                duration: 1000,
                easing: 'out'
            },
            hAxis: {
                minValue: 0
            }
        };

        // Draw the chart in the specified element
        const chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);

        // Redraw the chart on window resize
        window.addEventListener('resize', () => chart.draw(data, options));

        // Redraw the chart when the sidebar is toggled
        document.getElementById('toggle-btn').addEventListener('click', () => {
            setTimeout(() => chart.draw(data, options), 300);
        });

        // Redraw the chart on tab visibility change
        document.addEventListener('visibilitychange', () => {
            if (document.visibilityState === 'visible') {
                chart.draw(data, options);
            }
        });
    }
</script>
