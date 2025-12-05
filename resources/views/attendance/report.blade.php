<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Absensi</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        table th {
            background-color: #909090;
            font-weight: bold;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            padding: 10px 0;
        }

        .page-break {
            page-break-after: always;
        }

        .nowrap {
            white-space: nowrap;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin-bottom: 4px; margin-top: 0;">LAPORAN ABSENSI</h2>
        <h3 style="margin-bottom: 4px; margin-top: 0;">
            Tanggal Laporan: {{ $start->locale('id')->translatedFormat('d F Y') }}
            {{ $start->format('dFY') == $end->format('dFY') ? '' : ' - ' . $end->locale('id')->translatedFormat('d F Y') }}
        </h3>
        @if (isset($employee))
            <h3 style="margin-bottom: 4px; margin-top: 0;">Nama Karyawan: {{ $employee->name }}</h3>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Jam Keluar</th>
                <th>Status Kehadiran</th>
                <th>Half Day</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $index => $attendance)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $attendance->employee->name }}</td>
                    <td>{{ \Carbon\Carbon::parse($attendance->date)->locale('id')->translatedFormat('d F Y') }}</td>
                    <td class="nowrap">{{ $attendance->start_time ?? '-' }}</td>
                    <td class="nowrap"
                        style="background-color: {{ $attendance->end_time == null ? '#FF6B6B' : 'transparent' }}">
                        {{ $attendance->end_time ?? '-' }}
                    </td>
                    <td>{{ $attendance->attendanceStatus->name }}</td>
                    <td style="background-color: {{ $attendance->half_day ? '#FF6B6B' : 'transparent' }}">
                        {{ $attendance->half_day ? 'Yes' : 'No' }}
                    </td>
                    <td>{{ $attendance->description ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
