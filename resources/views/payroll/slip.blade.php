<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Slip Gaji - {{ $slip->employee->name }} - {{ $slip->period_name }}</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
            font-size: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }

        table th {
            background-color: #b1b1b1;
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

        .nowrap {
            white-space: nowrap;
        }

        .text-end {
            text-align: right;
        }

        .section-title {
            font-weight: bold;
            margin: 8px 0 4px 0;
            font-size: 11px;
        }
    </style>
</head>

<body>
    <table style="width:100%; border:none; margin-bottom: 6px;">
        <tr>
            <td style="width: 20%; border:none; text-align:left; vertical-align:middle;">
                <img src="{{ public_path('images/logo/' . $slip->employee->branch->company->codename . '.png') }}"
                    alt="Logo" style="width:80px;">
            </td>

            <td style="width: 80%; border:none; text-align:center; vertical-align:middle;">
                <div style="font-size:18px; font-weight:bold;">
                    {{ $slip->employee->branch->company->company_brand ?? 'NAMA PERUSAHAAN' }}</div>
                <div style="font-size:12px;">{{ $slip->employee->branch->address ?? 'Alamat perusahaan...' }}</div>
                <div style="font-size:12px;">Telp: {{ $slip->employee->branch->telephone ?? '-' }} |
                    Email: {{ $slip->employee->branch->company->email ?? '-' }} | Website:
                    {{ $slip->employee->branch->company->website ?? '-' }}</div>
            </td>
        </tr>
    </table>

    <hr style="border: 0; border-top: 2px solid #000; margin: 4px 0 10px 0;">

    <div class="header">
        <h2 style="margin-bottom: 4px; margin-top: 0;">SLIP GAJI</h2>
        <h3 style="margin-bottom: 4px; margin-top: 0;">
            Periode: {{ str_replace('Payroll ', ' ', $slip->period_name) }}
        </h3>
    </div>

    <table>
        <tr>
            <th style="width: 10%;">Nama Karyawan</th>
            <td style="width: 40%;">{{ $slip->employee->name }}</td>
            <th style="width: 10%;">Jabatan</th>
            <td style="width: 40%;">{{ $slip->employee->jobTitle->name ?? '-' }}</td>
        </tr>
        <tr>
            <th style="width: 10%;">NPK</th>
            <td style="width: 40%;">{{ $slip->employee->npk ?? '-' }}</td>
            <th style="width: 10%;">Cabang</th>
            <td style="width: 40%;">{{ $slip->employee->branch->name ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <tr>
            <td style="width: 50%; vertical-align: top;">
                <div class="section-title">Rekap Kehadiran</div>
                <table style="margin-bottom: 0;">
                    <tr>
                        <th style="width: 20%;">Hari Kerja</th>
                        <td style="width: 80%;">{{ $slip->days }}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Hadir</th>
                        <td style="width: 80%;">{{ $slip->present_days }}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Sakit</th>
                        <td style="width: 80%;">{{ $slip->sick_days }}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Izin</th>
                        <td style="width: 80%;">{{ $slip->permission_days }}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Cuti</th>
                        <td style="width: 80%;">{{ $slip->leave_days }}</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Tanpa Keterangan</th>
                        <td style="width: 80%;">{{ $slip->absent_days }} Hari</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Setengah Hari</th>
                        <td style="width: 80%;">{{ $slip->half_days }} Hari</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Terlambat</th>
                        <td style="width: 80%;">{{ $slip->late_minutes }} Menit</td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Lembur</th>
                        <td style="width: 80%;">{{ $slip->overtime_minutes }} Menit</td>
                    </tr>
                </table>
            </td>

            <td style="width: 50%; vertical-align: top;">
                <div class="section-title">Rincian Penghasilan</div>
                <table style="margin-bottom: 0;">
                    <tr>
                        <th style="width: 20%;">Gaji Pokok</th>
                        <td style="width: 80%;" class="text-end">{{ number_format($slip->base_salary, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Tunjangan</th>
                        <td style="width: 80%;" class="text-end">{{ number_format($slip->allowances, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Kompensasi</th>
                        <td style="width: 80%;" class="text-end">{{ number_format($slip->compensation, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 20%;">Potongan</th>
                        <td style="width: 80%;" class="text-end">{{ number_format($slip->deductions, 0, ',', '.') }}
                        </td>
                    </tr>
                    <tr>
                        <th style="width: 20%;"><strong>Total Diterima</strong></th>
                        <td style="width: 80%;" class="text-end">
                            <strong>{{ number_format($slip->net_salary, 0, ',', '.') }}</strong>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <table style="margin-top: 20px;">
        <tr>
            <td style="width: 50%; text-align: center; border: none;">
                @php
                    $kotakab = $slip->employee->branch->village->district->city->name ?? '................';
                    $kotakab = str_ireplace(
                        ['KOTA ', 'KABUPATEN ', 'KAB ', 'KOTA ADM. ', 'KABUPATEN ADM. '],
                        '',
                        $kotakab,
                    );
                    $kotakab = strtolower($kotakab);
                    $kotakab = ucwords($kotakab);
                @endphp
                {{ $kotakab }},
                {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}<br>
                Mengetahui,<br><br><br><br>
                ____________________________
                <br>
                <br>
                ( Manager Finance )
            </td>

            <td style="width: 50%; text-align: center; border: none;">
                Diterima Oleh,<br><br><br><br><br>
                ____________________________
                <br>
                <br>
                ( {{ $slip->employee->name }} )
            </td>
        </tr>
    </table>
    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i:s') }}
    </div>
</body>

</html>
