<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Training Karyawan - {{ $document->employee->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        .table th,
        .table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #909090;
            font-weight: bold;
        }

        .document-container {
            width: 90%;
            margin: 0 auto;
            padding: 20px 0;
        }

        .header-content {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            table-layout: fixed;
        }

        .header-logo {
            width: 15%;
            vertical-align: middle;
            text-align: left;
            padding: 0;
        }

        .header-info {
            width: 85%;
            vertical-align: middle;
            text-align: center;
            padding: 0;
        }

        .company-brand {
            font-size: 20px;
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            line-height: 1.2;
        }

        .company-address {
            font-size: 11px;
            display: block;
            line-height: 1.4;
        }

        .document-title {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            font-size: 14px;
            margin-top: 15px;
            margin-bottom: 15px;
        }

        .employee-details table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .employee-details td {
            padding: 2px 0;
        }

        .content {
            text-align: justify;
            margin-bottom: 20px;
        }

        .content p {
            margin: 0 0 10px 0;
        }

        .indented-list {
            margin-left: 30px;
            padding-left: 0;
            list-style-type: none;
        }

        .indented-list li {
            margin-bottom: 5px;
        }

        .signature-block {
            margin-top: 40px;
            width: 100%;
            border-collapse: collapse;
        }

        .signature-block td {
            vertical-align: top;
            padding: 0;
        }

        .signature-details {
            text-align: center;
        }

        .signature-space {
            height: 50px;
            display: block;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 9px;
            padding-bottom: 5px;
        }

        .hr-line {
            border: 0;
            border-top: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    <div class="document-container">
        <table class="header-content">
            <tr>
                <td class="header-logo">
                    <img src="{{ public_path('images/logo/' . $document->employee->branch->company->codename . '.png') }}"
                        alt="Logo" style="width:75px; height: auto;">
                </td>
                <td class="header-info">
                    <span class="company-brand">
                        {{ $document->employee->branch->company->company_brand }}
                    </span>
                    <span class="company-address">
                        {{ $document->employee->branch->address }}<br>
                        Telp. {{ $document->employee->branch->telephone }}
                    </span>
                </td>
            </tr>
        </table>
        <hr class="hr-line">
        <p class="document-title">
            Detail Training Karyawan
        </p>
        <div class="employee-details">
            <table>
                <tr>
                    <td>Nama</td>
                    <td>:</td>
                    <td><strong>{{ $document->employee->name }}</strong></td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td><strong>{{ $document->employee->jobTitle->name }}</strong></td>
                </tr>
                <tr>
                    <td>Cabang</td>
                    <td>:</td>
                    <td><strong>{{ $document->employee->branch->name ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Jenis Training</td>
                    <td>:</td>
                    <td><strong>{{ $document->trainingType->name ?? '-' }}</strong></td>
                </tr>
                <tr>
                    <td>Nama Training</td>
                    <td>:</td>
                    <td><strong>{{ $document->training_name ?? '-' }}</strong></td>
                </tr>
            </table>
        </div>
        <div class="content">
            <table class="table">
                <thead>
                    <tr class="nowrap">
                        <th>#</th>
                        <th>Pembahasan</th>
                        <th>Waktu</th>
                        <th>Mentor</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($document->schedules as $index => $item)
                        <tr class="nowrap">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->schedule_time)->locale('id')->translatedFormat('d M Y H:i') }}
                            </td>
                            <td>{{ $item->mentor->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center;">
                                Tidak ada data
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>


        <div class="footer">
            Dicetak pada: {{ now()->locale('id')->translatedFormat('d F Y H:i:s') }}
        </div>
    </div>
</body>

</html>
