<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Subdomain</title>
    <style>
        body {
            font-family: sans-serif;
            background-color: #f9f9f9;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        .container {
            display: inline-block;
            padding: 2rem 3rem;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #007BFF;
        }

        p {
            font-size: 1.1rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>API Endpoint Aktif</h1>
        <p>Anda berada di subdomain (<strong>{{ str_replace(['http://', 'https://'], '', route('home')) }}</strong>).
        </p>
        <p>Halaman ini hanya sebagai penyedia data dari perusahaan.</p>
        <p>Silakan hubungi admin untuk penggunaan aplikasi terkait.</p>
    </div>
</body>

</html>
