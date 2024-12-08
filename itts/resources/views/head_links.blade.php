<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/psu-logo.png') }}">

    <title>Internship Time Tracker</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined " />
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Toaster --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>


    <style>
        @tailwind base;
        @tailwind components;
        @tailwind utilities;

        /* Google Fonts - Poppins */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap');

        body {
            font-family: 'Poppins', sans-serif;

        }

        .slide-in {
            transform: translateX(0);
            transition: transform 0.3s ease-in-out;
        }

        .slide-out {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .overlay {
            backdrop-filter: blur(20px);
        }
    </style>
</head>

<body 
    class=" text-black">
