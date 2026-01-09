<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Training Karyawan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
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

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin-bottom: 4px; margin-top: 0;">LAPORAN TRAINING KARYAWAN</h2>
        @php
            function parseCarbon($value)
            {
                $data = \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d M Y');
                return $data;
            }
            $start_date = !is_null($start) ? parseCarbon($start, true) : null;
            $end_date = !is_null($end) ? parseCarbon($end, true) : null;
        @endphp
        @if (!is_null($start_date) && !is_null($end_date))
            <h3 style="margin-bottom: 4px; margin-top: 0;">
                @if ($start_date === $end_date)
                    Data Periode {{ $start_date }}
                @else
                    Data Periode {{ $start_date }} - {{ $end_date }}
                @endif
            </h3>
        @endif
    </div>

    <table>
        <thead>
            <tr class="nowrap">
                <th>#</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Tipe</th>
                <th>Status</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $data)
                <tr class="nowrap">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->employee->name }}</td>
                    <td>{{ parseCarbon($data->start_date) }}</td>
                    <td>{{ $data->trainingType?->name }}</td>
                    <td>{{ $data->status ? 'Selesai' : 'Belum Selesai' }}</td>
                    <td style="white-space: wrap;">{!! $data->notes !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <h2 style="text-align: center;">
                            Tidak ada data
                        </h2>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
