{{-- Header - Links - CSS --}}
@include('admin.links_css')

{{-- Sidebar --}}
@include('admin.sidebar')

<!-- Main content -->
<div id="main-content" class="flex-1 ml-64 transition-all content-adjust">
    <!-- Fixed Header -->
    <header
        class="bg-gradient-to-l from-blue-300 to-blue-700 text-white p-4 flex justify-between items-center fixed top-0 left-0 right-0 z-10">
        <button id="toggle-btn" class="text-2xl">☰</button>
        <h1 class="text-xl font-bold tracking-wider">Admin Dashboard</h1>
    </header>

    <!-- Dashboard content -->
    <section class="p-6">
        <h1 class="text-xl font-semibold">Dashboard</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 my-4">

            {{-- First Card --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-full">
                <div class="flex justify-between items-center p-2 w-full">
                    <!-- Text Section -->
                    <div class="flex flex-col">
                        <span class="font-medium text-sm">Total Offices</span>
                        <p class="font-semibold text-3xl">{{ $totalOffices }}</p>
                    </div>
                    <!-- Icon Section -->
                    <div>
                        <span
                            class="material-icons text-2xl bg-cover h-full w-10 text-center rounded-sm bg-blue-500 text-white">business</span>
                    </div>
                </div>
            </div>

            {{-- Second Card --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-full">
                <div class="flex justify-between items-center p-2 w-full">
                    <!-- Text Section -->
                    <div class="flex flex-col">
                        <span class="font-medium text-sm">Total Interns</span>
                        <p class="font-semibold text-3xl">{{ $totalInterns }}</p>
                    </div>
                    <!-- Icon Section -->
                    <div>
                        <span
                            class="material-icons text-2xl bg-cover h-full w-10 text-center rounded-sm bg-yellow-400 text-white">people</span>
                    </div>
                </div>
            </div>

            {{-- Third Card --}}
            <div class="shadow-sm shadow-gray-700 rounded h-30 bg-white w-full ">
                <div class="flex flex-col">
                    <span id="current-date" class="font-medium text-sm mt-2 mx-2">--:--:--</span>
                    <p id="real-time-clock" {{-- style="-webkit-text-stroke: 0.5px #fff; text-shadow: 1px 1px 4px rgba(104, 103, 103, 0.6);" --}}
                        class="tracking-wider text-black font-semibold text-3xl text-center">--:--:--</p>
                </div>
            </div>

        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 my-4">

            <!-- Bar Chart: 8/12 columns on large screens, full-width on small screens -->
            <div class="lg:col-span-12 shadow-sm shadow-gray-700 h-96 bg-white rounded">
                <div id="column-chart" class="w-full h-full p-1"></div>
            </div>

            <!-- Pie Chart: 4/12 columns on large screens, full-width on small screens -->
            {{-- <div class="lg:col-span-4 shadow-sm shadow-gray-700 h-96 bg-white rounded">
                <div id="pie-chart" class="w-full h-full p-1"></div>
            </div> --}}

        </div>

    </section>
</div>

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


@include('admin.footer')

<!-- Load Google Charts Library -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<script type="text/javascript">
    // Load Google Charts library
    google.charts.load('current', {
        packages: ['corechart', 'bar']
    });

    // Set a callback to draw charts once Google Charts is loaded
    google.charts.setOnLoadCallback(drawCharts);

    // Function to initialize both charts
    function drawCharts() {
        drawColumnChart(); // Draw the column chart
        // drawPieChart();    // Draw the pie chart (placeholder)
    }

    // Function to draw the column chart
    function drawColumnChart() {
        // Dynamic data for the chart
        var data = google.visualization.arrayToDataTable([
            ['Office', 'Number of Students', {
                role: 'style'
            }],
            @foreach ($officeData as $index => $office)
                ['{{ $office->name }}', {{ $office->interns_count }}, getColor(
                    {{ $index }})], // Dynamic color
            @endforeach
        ]);

        // Chart options
        var options = {
            title: 'Students Assigned to Offices',
            titleTextStyle: {
                fontSize: 18,
                bold: true,
                alignment: 'center'
            },
            chartArea: {
                width: '70%'
            },
            legend: {
                position: 'none'
            },
            animation: {
                startup: true,
                duration: 800,
                easing: 'out'
            },
            annotations: {
                alwaysOutside: true,
                textStyle: {
                    fontSize: 12,
                    color: '#000'
                }
            }
        };

        // Draw the column chart
        var chart = new google.visualization.ColumnChart(document.getElementById('column-chart'));
        chart.draw(data, options);

        // Make the chart responsive on window resize
        window.addEventListener('resize', function() {
            chart.draw(data, options);
        });
    }

    // Function to draw the pie chart (placeholder)
    // function drawPieChart() {
    //     var data = google.visualization.arrayToDataTable([
    //         ['Status', 'Number of Students'],
    //         ['Present', 80],
    //         ['Absent', 20]
    //     ]);

    //     var options = {
    //         title: 'Student Attendance Today',
    //         titleTextStyle: {
    //             fontSize: 18,
    //             bold: true,
    //             alignment: 'center'
    //         },
    //         pieHole: 0.4,
    //         chartArea: {
    //             width: '70%',
    //             height: '70%'
    //         },
    //         animation: {
    //             startup: true,
    //             duration: 1000,
    //             easing: 'out'
    //         },
    //         legend: {
    //             position: 'bottom'
    //         },
    //         slices: {
    //             0: { color: '#22c55e' }, // Green for "Present"
    //             1: { color: '#ff2c2c' }  // Red for "Absent"
    //         }
    //     };

    //     // Draw the pie chart
    //     var chart = new google.visualization.PieChart(document.getElementById('pie-chart'));
    //     chart.draw(data, options);

    //     // Make the pie chart responsive on window resize
    //     window.addEventListener('resize', function () {
    //         chart.draw(data, options);
    //     });
    // }

    // JavaScript helper function to generate dynamic colors for offices
    function getColor(index) {
        const colors = ['#ef4444', '#facc15', '#3b82f6', '#c026d3', '#22c55e']; // Specific colors
        return colors[index % colors.length]; // Loop through colors if more offices exist
    }
</script>
