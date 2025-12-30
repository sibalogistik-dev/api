<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Peringatan - {{ $document->employee->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }

        /* Container utama untuk dokumen, berguna untuk margin cetak */
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
            /* Opsional: menjaga lebar tetap stabil */
        }

        .header-logo {
            width: 15%;
            /* Sesuaikan, misal 15-20% */
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

        /* Styling untuk daftar pelanggaran/instruksi */
        .indented-list {
            margin-left: 30px;
            padding-left: 0;
            list-style-type: none;
            /* Hilangkan bullet default */
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
            /* Jarak untuk tanda tangan */
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
        @php
            function spConvert($value)
            {
                switch ($value) {
                    case '1':
                        return 'Pertama';
                    case '2':
                        return 'Kedua';
                    case '3':
                        return 'Ketiga';
                    default:
                        return $value;
                }
            }
            function genNumb($id)
            {
                return str_pad($id, 5, '0', STR_PAD_LEFT);
            }

            function documentNumber($data)
            {
                $numb = genNumb($data->id);
                $docNumb = '0' . $data->letter_number . '/SP/' . $numb . '/' . $data->letter_date->format('Y');

                return $docNumb;
            }
        @endphp

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
            SURAT PERINGATAN
        </p>
        <div class="employee-details">
            <table>
                <tr>
                    <td width="15%">Nomor Dokumen</td>
                    <td width="2%">:</td>
                    <td><strong>{{ documentNumber($document) }}</strong></td>
                </tr>
                <tr>
                    <td>Kepada Yth.</td>
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
            </table>
        </div>
        <div class="content">
            <p>
                Dengan ini perusahaan memberikan <strong>Surat Peringatan {{ $document->letter_number }}
                    ({{ spConvert($document->letter_number) }})</strong> kepada karyawan tersebut di atas atas
                pelanggaran disiplin kerja berupa:
            </p>

            <ul class="indented-list">
                <li>&bull; <strong>{{ $document->reason }}</strong></li>
            </ul>

            <p>
                Adapun catatan dan instruksi yang harus diperhatikan adalah sebagai berikut:
            </p>

            <ul class="indented-list">
                <li>&bull; {{ $document->notes }}</li>
            </ul>

            <p>
                Surat peringatan ini berlaku sejak tanggal
                <strong>{{ $document->letter_date->format('d F Y') }}</strong>.
                Apabila di kemudian hari terjadi pelanggaran serupa, maka perusahaan akan mengambil tindakan lanjutan
                sesuai peraturan dan kebijakan perusahaan yang berlaku.
            </p>
        </div>

        <table class="signature-block">
            <tr>
                <td width="50%" class="signature-details">
                    <p>
                        Karyawan yang bersangkutan,<br><br>
                        <span class="signature-space"></span>
                        <strong>{{ $document->employee->name }}</strong>
                    </p>
                </td>
                <td width="50%" class="signature-details">
                    <p>
                        {{ $document->employee->branch->city ?? 'Jakarta' }},
                        {{ $document->letter_date->format('d F Y') }}<br>
                        Diterbitkan oleh,<br>
                        <span class="signature-space"></span>
                        <strong>{{ $document->issuer->name }}</strong><br>
                        {{ $document->issuer->jobTitle->name ?? 'HRD' }}
                    </p>
                </td>
            </tr>
        </table>
        <div class="footer">
            Dicetak pada: {{ now()->locale('id')->translatedFormat('d F Y H:i:s') }}
        </div>
    </div>
</body>

</html>
