<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Timesheet</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h2>Timesheet for {{ $intern->user->fname }} {{ $intern->user->lname }}</h2>
    <p>Office: {{ $intern->office->name ?? 'N/A' }}</p>
    <p>Total Working Hours: {{ number_format($totalWorkingHours, 2) }} hrs</p>
    <p>Date Printed: {{ $currentDate }}</p>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>AM Clock In</th>
                <th>AM Clock Out</th>
                <th>PM Clock In</th>
                <th>PM Clock Out</th>
                <th>Daily Working Hours</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($attendance as $attend)
                @php
                    $amHours = $attend->am_clock_in && $attend->am_clock_out
                        ? \Carbon\Carbon::parse($attend->am_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->am_clock_out)) / 60
                        : 0;
                    $pmHours = $attend->pm_clock_in && $attend->pm_clock_out
                        ? \Carbon\Carbon::parse($attend->pm_clock_in)->diffInMinutes(\Carbon\Carbon::parse($attend->pm_clock_out)) / 60
                        : 0;
                    $dailyHours = $amHours + $pmHours;
                @endphp
                <tr>
                    <td>{{ $attend->date }}</td>
                    <td>{{ $attend->am_clock_in ? \Carbon\Carbon::parse($attend->am_clock_in)->format('h:i A') : '—' }}</td>
                    <td>{{ $attend->am_clock_out ? \Carbon\Carbon::parse($attend->am_clock_out)->format('h:i A') : '—' }}</td>
                    <td>{{ $attend->pm_clock_in ? \Carbon\Carbon::parse($attend->pm_clock_in)->format('h:i A') : '—' }}</td>
                    <td>{{ $attend->pm_clock_out ? \Carbon\Carbon::parse($attend->pm_clock_out)->format('h:i A') : '—' }}</td>
                    <td>{{ $dailyHours > 0 ? number_format($dailyHours, 2) . ' hrs' : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
