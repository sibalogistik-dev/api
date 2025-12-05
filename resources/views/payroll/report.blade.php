<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Laporan Penggajian {{ $period }}</title>
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
        <h2 style="margin-bottom: 4px; margin-top: 0;">LAPORAN PENGGAJIAN</h2>
        <h3 style="margin-bottom: 4px; margin-top: 0;">
            Periode Penggajian: {{ $period }}
        </h3>
    </div>

    <table>
        <thead>
            <tr class="nowrap">
                <th>#</th>
                <th>Nama</th>
                <th>Hari Kerja</th>
                <th>Hadir</th>
                <th>Sakit</th>
                <th>Izin</th>
                <th>Cuti</th>
                <th>Libur</th>
                <th>Tnp. Ket.</th>
                <th>Stg. Hari</th>
                <th>Terlambat</th>
                <th>Lembur</th>
                <th>Gapok</th>
                <th>Tunj.</th>
                <th>Pot.</th>
                <th>Kompensasi</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($report as $index => $data)
                <tr class="nowrap">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $data->employee->name }}</td>
                    <td>{{ $data->days }}</td>
                    <td>{{ $data->present_days }}</td>
                    <td>{{ $data->sick_days }}</td>
                    <td>{{ $data->permission_days }}</td>
                    <td>{{ $data->leave_days }}</td>
                    <td>{{ $data->off_days }}</td>
                    <td>{{ $data->absent_days }}</td>
                    <td>{{ $data->half_days }}</td>
                    <td>{{ $data->late_minutes }} Menit</td>
                    <td>{{ $data->overtime_minutes }} Menit</td>
                    <td class="text-end">{{ number_format($data->base_salary, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($data->allowances, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($data->deductions, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($data->compensation, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($data->net_salary, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="nowrap">
                <th colspan="10" class="text-end">TOTAL</th>
                <th class="text-end">
                    {{ $report->sum('late_minutes') }} Menit
                </th>
                <th class="text-end">
                    {{ $report->sum('overtime_minutes') }} Menit
                </th>
                <th class="text-end">
                    {{ number_format($report->sum('base_salary'), 0, ',', '.') }}
                </th>
                <th class="text-end">
                    {{ number_format($report->sum('allowances'), 0, ',', '.') }}
                </th>
                <th class="text-end">
                    {{ number_format($report->sum('deductions'), 0, ',', '.') }}
                </th>
                <th class="text-end">
                    {{ number_format($report->sum('compensation'), 0, ',', '.') }}
                </th>
                <th class="text-end">
                    {{ number_format($report->sum('net_salary'), 0, ',', '.') }}
                </th>
            </tr>
        </tfoot>
    </table>
    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
