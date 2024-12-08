<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/psu-logo.png') }}">
    <title>Admin Panel</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined " />

    {{-- Fontawesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    {{-- Box Icons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
    
    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        /* Google Fonts - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

        .sidebar-active {
            transform: translateX(0);
        }

        .sidebar-hidden {
            transform: translateX(-100%);
        }

        .transition-all {
            transition: all 0.3s ease;
        }

        .content-adjust {
            padding-top: 64px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            height: 100vh;
            overflow-x: hidden;
        }

        .sidebar {
            width: 250px;
            background-color: #333;
            color: white;
            padding: 20px;
            position: fixed;
            height: 100%;
            left: 0;
            top: 0;
            transition: transform 0.3s ease;
        }

        .sidebar ul {
            list-style-type: none;
        }

        .sidebar ul li {
            margin: 15px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        .sidebar ul li a:hover {
            color: #ffcc00;
        }

        .main-content {
            flex: 1;
            padding: 20px;
            background-color: #f4f4f4;
            margin-left: 250px;
            transition: margin-left 0.3s ease;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
        }

        .toggle-btn {
            font-size: 24px;
            background: none;
            border: none;
            color: white;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-250px);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.active {
                margin-left: 250px;
            }
        }

        .hover-custom-blue:hover {
            color: #4050B8;
        }

        .transition-colors {
            transition: color 0.3s ease;
        }

        .material-icons {
            font-size: 1.25rem;
        }

        .logo-container {
            display: flex;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .logo-container img {
            margin-right: 12px;
        }
    </style>
</head>

<body class="flex min-h-screen bg-gray-200">
