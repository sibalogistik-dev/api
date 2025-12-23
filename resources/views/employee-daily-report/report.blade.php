<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Harian Karyawan</title>
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
        <h2 style="margin-bottom: 4px; margin-top: 0;">LAPORAN HARIAN KARYAWAN</h2>
        @php
            function parseCarbon($value, $is_translate = false)
            {
                if ($is_translate) {
                    $data = \Carbon\Carbon::parse($value)->locale('id')->translatedFormat('d F Y');
                } else {
                    $data = \Carbon\Carbon::parse($value)->format('d F Y');
                }
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
                <th>Jabatan</th>
                <th>Cabang</th>
                <th>Tanggal</th>
                <th>Deskripsi Pekerjaan</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($report as $index => $data)
                <tr class="nowrap">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->employee->name }}</td>
                    <td>{{ $data->employee->jobTitle->name }}</td>
                    <td>{{ $data->employee->branch->name }}</td>
                    <td>{{ parseCarbon($data->date, true) }}</td>
                    <td style="white-space: wrap;">
                        {!! optional($data->employee->jobTitle->jobDescriptions)->task_name ?? '-' !!}
                    </td>
                    <td style="white-space: wrap;">{!! $data->description !!}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">
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
