@include('head_links')
@include('navbar')

<div id="mainContent"
    class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-indigo-50 flex flex-col items-center justify-start pt-16 px-4 md:px-6 lg:px-8">
    <!-- Time and Date Display -->
    <div class="text-center mb-8 mt-4 animate-fade-in">
        <h1 id="timeDisplay" class="text-4xl md:text-6xl lg:text-7xl font-bold text-gray-800 tracking-tight font-mono">
        </h1>
        <p id="dateDisplay" class="text-lg md:text-xl text-gray-600 mt-2 font-medium"></p>
    </div>

    <!-- Clock-In/Clock-Out Form -->
    <form id="clockInOutForm" method="POST" action="{{ route('intern.clockinout') }}">
        @csrf
        <input type="hidden" name="action" id="clockInOutAction" value="{{ $status }}">
        <input type="hidden" name="image" id="capturedImage" value="">

        <!-- Camera Video and Canvas -->
        <div id="camera-container" style="display: none;">
            <video id="video" autoplay></video>
            <button type="button" onclick="captureImage()"
                class="px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg shadow hover:from-blue-600 hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition ease-in-out duration-300">
                Capture
            </button>
            <canvas id="canvas" style="display:none;"></canvas>
        </div>

        <!-- Clock-In/Clock-Out Button -->
        <button type="button" onclick="openCamera()" id="clockInOutBtn"
            class="w-40 h-40 mb-4 group relative shadow-md bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white rounded-full transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-blue-300 focus:ring-opacity-50 overflow-hidden flex items-center justify-center mx-auto">
            <div class="flex flex-col items-center">
                <span class="material-symbols-outlined text-5xl group-hover:animate-bounce">touch_app</span>
                <span id="clockInOutText" class="text-sm font-semibold mt-1">
                    {{ $status == 'clock-in' ? 'Clock In' : 'Clock Out' }}
                </span>
            </div>
        </button>
    </form>

    <!-- Location Info -->
    <div class="flex justify-center mb-6">
        <div class="flex items-center max-w-md w-full">
            <div class="flex flex-col justify-center text-left mt-4">
                <p class="text-sm text-center text-gray-500 font-semibold">Location</p>
                <p class="text-xl font-extrabold text-center text-black">{{ $location }}</p>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
{{-- <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update Date and Time Display
        function updateDateTime() {
            const now = new Date();
            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('timeDisplay').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('dateDisplay').textContent = now.toLocaleDateString('en-US', dateOptions);
        }

        // Initialize Date and Time Updates
        updateDateTime();
        setInterval(updateDateTime, 1000);
    });

    // For photo capturing
    let video = null;
    let canvas = null;

    function openCamera() {
        console.log('Attempting to open the camera...');
        document.getElementById('camera-container').style.display = 'block';
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                console.log('Camera is active.');
                video.srcObject = stream;
            })
            .catch((err) => {
                console.error('Error accessing the camera:', err.message);
                alert('Unable to access the camera. Please check your permissions and try again.');
            });
    }

    function captureImage() {
        console.log('Capturing image...');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/png');
        console.log('Captured Image:', imageData); // Debug the captured image
        document.getElementById('capturedImage').value = imageData;

        // Stop the video stream
        const stream = video.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        console.log('Camera stream stopped.');

        // Submit the form
        document.getElementById('clockInOutForm').submit();
    }
</script> --}}
{{-- const predefinedDate = new Date('2024-11-20T15:00:00'); --}}

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // const predefinedDate = new Date('2024-11-12 17:00:00'); // Local time

        // Update Date and Time Display
        function updateDateTime() {
            const now = new Date(); // Get the current date and time
            // predefinedDate.setSeconds(predefinedDate.getSeconds() + 1);

            const timeOptions = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true
            };
            const dateOptions = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };

            document.getElementById('timeDisplay').textContent = now.toLocaleTimeString('en-US', timeOptions);
            document.getElementById('dateDisplay').textContent = now.toLocaleDateString('en-US', dateOptions);
            // document.getElementById('timeDisplay').textContent = predefinedDate.toLocaleTimeString('en-US', timeOptions);
            // document.getElementById('dateDisplay').textContent = predefinedDate.toLocaleDateString('en-US', dateOptions); //predefined time
        }

        // Initialize Date and Time Updates
        updateDateTime(); // Initial call to set the time on page load

        // Update the time every second
        setInterval(updateDateTime, 1000);

        // Get the initial clock-in/out status
        const clockInOutAction = document.getElementById('clockInOutAction').value;
        const clockInOutBtn = document.getElementById('clockInOutBtn');
        const clockInOutText = document.getElementById('clockInOutText');

        // Function to toggle button text based on clock-in/out status
        function updateButtonState(action) {
            if (action === 'clock-in') {
                clockInOutText.textContent = 'Clock In'; // Change text to "Clock In"
            } else {
                clockInOutText.textContent = 'Clock Out'; // Change text to "Clock Out"
            }
        }

        // Set the button state on page load based on initial action
        updateButtonState(clockInOutAction);

        // Toggle between clock-in and clock-out states when the button is clicked
        clockInOutBtn.addEventListener('click', function() {
            const currentAction = document.getElementById('clockInOutAction').value;

            // If clocked-in, switch to clock-out
            if (currentAction === 'clock-in') {
                document.getElementById('clockInOutAction').value = 'clock-out';
                updateButtonState('clock-out');
            } else { // If clocked-out, switch to clock-in
                document.getElementById('clockInOutAction').value = 'clock-in';
                updateButtonState('clock-in');
            }
        });
    });

    // For photo capturing
    let video = null;
    let canvas = null;

    function openCamera() {
        console.log('Attempting to open the camera...');
        document.getElementById('camera-container').style.display = 'block';
        video = document.getElementById('video');
        canvas = document.getElementById('canvas');

        navigator.mediaDevices.getUserMedia({
                video: true
            })
            .then((stream) => {
                console.log('Camera is active.');
                video.srcObject = stream;
            })
            .catch((err) => {
                console.error('Error accessing the camera:', err.message);
                alert('Unable to access the camera. Please check your permissions and try again.');
            });
    }

    function captureImage() {
        console.log('Capturing image...');
        const context = canvas.getContext('2d');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        context.drawImage(video, 0, 0, canvas.width, canvas.height);

        const imageData = canvas.toDataURL('image/png');
        console.log('Captured Image:', imageData); // Debug the captured image
        document.getElementById('capturedImage').value = imageData;

        // Stop the video stream
        const stream = video.srcObject;
        const tracks = stream.getTracks();
        tracks.forEach((track) => track.stop());
        console.log('Camera stream stopped.');

        // Submit the form
        document.getElementById('clockInOutForm').submit();
    }
</script>



<!-- Styles -->
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.4s linear forwards;
    }

    .material-symbols-outlined {
        font-variation-settings:
            'FILL' 1,
            'wght' 400,
            'GRAD' 0,
            'opsz' 48;
    }

    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

@include('toggle_footer')
