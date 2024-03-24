<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
        }

        th,
        td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>

<body>
    <h2>Awesome Data</h2>
    <table>
        <tr>
            <th style="width: 10%">Date</th>
            <th style="width: 40%">Time Tracker Data</th>
            <th style="width: 10%">Break</th>
            <th style="width: 10%">Worked Hours</th>
            <th style="width: 10%">Hours Difference</th>
        </tr>
        @foreach ($selected_dates as $selected_date)
        <tr>
            <td>{{ $selected_date['date'] }}</td>
            @php
            $date = $selected_date['date'];
            $data = $time_tracker_data->where('date', $date);

            $break_seconds = 0;
            $worked_seconds = 0;
            @endphp
            <td>
                @if (!empty($data))
                <table>
                    <tr>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Start Time</th>
                        <th>End Time</th>

                    </tr>
                    @foreach ($data as $item)
                    <tr>
                        <td>{{$item['type']}}</td>
                        <td>{{$item['description']}}</td>
                        <td>{{$item['start_time']}}</td>
                        <td>{{$item['end_time']}}</td>
                    </tr>
                    @php
                    $diff = strtotime($item['end_time']) - strtotime($item['start_time']);
                    if ($item['type'] == 'break' || $item['type'] == 'take-from-overdue') {
                    $break_seconds += $diff;
                    } else {
                    $worked_seconds += $diff;
                    }
                    @endphp
                    @endforeach
                </table>
                @endif
            </td>
            <td>{{$break_seconds / 3600}}</td>
            <td>{{$worked_seconds / 3600}}</td>
            <td>{{$worked_seconds / 3600 - $selected_date['expectedHours']}}</td>
        </tr>
        @endforeach
    </table>
</body>

</html>