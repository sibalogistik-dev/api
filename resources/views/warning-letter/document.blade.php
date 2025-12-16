<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Peringatan</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 10px;
        }

        .content {
            text-align: justify;
        }

        table {
            width: 100%;
            font-size: 12px;
        }

        .signature {
            margin-top: 40px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            font-size: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <table style="width:90%; margin:0 auto;">
            <tr>
                <td width="10%" style="text-align:left; vertical-align:middle;">
                    <img src="{{ public_path('images/logo/' . $document->employee->branch->company->codename . '.png') }}"
                        alt="Logo Perusahaan" style="width:95px;">
                </td>
                <td width="85%" style="text-align:center; vertical-align:middle;">
                    <strong style="font-size:24px;">
                        {{ $document->employee->branch->company->company_brand }}
                    </strong>
                    <br>
                    <span style="font-size:14px;">
                        {{ $document->employee->branch->address }}<br>
                        Telp. {{ $document->employee->branch->telephone }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
    <hr style="border:1px solid #000; margin-top:10px;">
    <p style="text-align:center; font-weight:bold; text-decoration:underline; font-size:16px;">
        SURAT PERINGATAN
    </p>
    <table>
        <tr>
            <td width="10%">Nama</td>
            <td width="5%">:</td>
            <td><strong>{{ $document->employee->name }}</strong></td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $document->employee->jobTitle->name }}</td>
        </tr>
    </table>
    <br>
    <div class="content">
        <p>
            Dengan ini perusahaan memberikan <strong>Surat Peringatan</strong>
            kepada karyawan tersebut di atas atas pelanggaran disiplin kerja berupa:
        </p>

        <p style="margin-left:20px;">
            <strong>{{ $document->reason }}</strong>
        </p>

        <p>
            Adapun catatan dan instruksi yang harus diperhatikan:
        </p>

        <p style="margin-left:20px;">
            {{ $document->notes }}
        </p>

        <p>
            Surat peringatan ini berlaku sejak tanggal
            <strong>{{ $document->letter_date->format('d F Y') }}</strong>.
            Apabila di kemudian hari terjadi pelanggaran serupa, maka perusahaan akan
            mengambil tindakan lanjutan sesuai peraturan yang berlaku.
        </p>
    </div>
    <table class="signature">
        <tr>
            <td width="60%"></td>
            <td>
                Jakarta, {{ $document->letter_date->format('d F Y') }}<br>
                Diterbitkan oleh,<br><br><br>
                <strong>{{ $document->issuer->name }}</strong><br>
                {{ $document->issuer->jobTitle->name ?? 'HRD' }}
            </td>
        </tr>
    </table>
    <div class="footer">
        Dicetak pada: {{ now()->locale('id')->translatedFormat('d M Y H:i:s') }}
    </div>
</body>

</html>
