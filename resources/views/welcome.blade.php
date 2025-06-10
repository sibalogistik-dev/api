<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/siba cargo.png') }}">
    <title>API Documentation</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            line-height: 1.6;
            padding: 30px;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 3rem;
            border: 1px solid #e1e4e8;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1,
        h2 {
            color: #0969da;
        }

        .endpoint-group {
            margin: 30px 0;
            border: 1px solid #e1e4e8;
            border-radius: 6px;
        }

        .endpoint-group h3 {
            margin: 0;
            padding: 16px;
            background: #f6f8fa;
            border-bottom: 1px solid #e1e4e8;
        }

        .endpoint-list {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        .endpoint-item {
            padding: 16px;
            border-bottom: 1px solid #e1e4e8;
        }

        .endpoint-item:last-child {
            border-bottom: none;
        }

        .method {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 0.9em;
            min-width: 65px;
            text-align: center;
        }

        .get {
            background: #ddf4ff;
            color: #0969da;
        }

        .post {
            background: #dafbe1;
            color: #1a7f37;
        }

        .put {
            background: #fff8c5;
            color: #9a6700;
        }

        .delete {
            background: #ffebe9;
            color: #cf222e;
        }

        .endpoint-path {
            margin-left: 12px;
            font-family: monospace;
            font-size: 1em;
        }

        .auth-badge {
            float: right;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8em;
            font-weight: 600;
        }

        .auth-required {
            background: #ffebe9;
            color: #cf222e;
        }

        .no-auth {
            background: #dafbe1;
            color: #1a7f37;
        }

        .description {
            margin-top: 8px;
            color: #57606a;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>API Documentation</h1>
        <p>Anda berada di subdomain (<strong>{{ str_replace(['http://', 'https://'], '', route('home')) }}</strong>)</p>

        <div class="endpoint-group">
            <h3>🔒 Protected Endpoints</h3>
            <ul class="endpoint-list">
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/user</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan informasi pengguna yang diautentikasi</div>
                </li>
                <li class="endpoint-item">
                    <strong>
                        Provinsi
                    </strong>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/provinsi</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan daftar provinsi</div>
                </li>
                <li class="endpoint-item">
                    <span class="method post">POST</span>
                    <span class="endpoint-path">/provinsi</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Membuat provinsi baru</div>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/provinsi/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan detail provinsi</div>
                </li>
                <li class="endpoint-item">
                    <span class="method put">PUT</span>
                    <span class="endpoint-path">/provinsi/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Memperbarui data provinsi</div>
                </li>
                <li class="endpoint-item">
                    <span class="method delete">DELETE</span>
                    <span class="endpoint-path">/provinsi/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Menghapus provinsi</div>
                </li>
                <li class="endpoint-item">
                    <strong>
                        Kota dan Kabupaten
                    </strong>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kotakab</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan daftar kota</div>
                </li>
                <li class="endpoint-item">
                    <span class="method post">POST</span>
                    <span class="endpoint-path">/kotakab</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Membuat kota baru</div>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kotakab/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan detail kota</div>
                </li>
                <li class="endpoint-item">
                    <span class="method put">PUT</span>
                    <span class="endpoint-path">/kotakab/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Memperbarui data kota</div>
                </li>
                <li class="endpoint-item">
                    <span class="method delete">DELETE</span>
                    <span class="endpoint-path">/kotakab/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Menghapus kota</div>
                </li>
                <li class="endpoint-item">
                    <strong>
                        Kecamatan
                    </strong>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kecamatan</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan daftar kecamatan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method post">POST</span>
                    <span class="endpoint-path">/kecamatan</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Membuat kecamatan baru</div>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kecamatan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan detail kecamatan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method put">PUT</span>
                    <span class="endpoint-path">/kecamatan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Memperbarui data kecamatan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method delete">DELETE</span>
                    <span class="endpoint-path">/kecamatan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Menghapus kecamatan</div>
                </li>
                <li class="endpoint-item">
                    <strong>
                        Kelurahan
                    </strong>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kelurahan</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan daftar kelurahan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method post">POST</span>
                    <span class="endpoint-path">/kelurahan</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Membuat kelurahan baru</div>
                </li>
                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/kelurahan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Mendapatkan detail kelurahan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method put">PUT</span>
                    <span class="endpoint-path">/kelurahan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Memperbarui data kelurahan</div>
                </li>
                <li class="endpoint-item">
                    <span class="method delete">DELETE</span>
                    <span class="endpoint-path">/kelurahan/{id}</span>
                    <span class="auth-badge auth-required">Butuh Autentikasi</span>
                    <div class="description">Menghapus kelurahan</div>
                </li>
            </ul>
        </div>

        <!-- Public Endpoints -->
        <div class="endpoint-group">
            <h3>🔓 Public Endpoints</h3>
            <ul class="endpoint-list">
                <li class="endpoint-item">
                    <span class="method post">POST</span>
                    <span class="endpoint-path">/login</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Authenticate user and get token</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/province/get</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get list of all provinces</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/province/get/{code}</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get province details by code</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/province/get/{code}/city</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get cities in a province</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/city/get/{code}</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get city details by code</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/city/get/{code}/district</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get districts in a city</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/district/get/{code}</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get district details by code</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/district/get/{code}/village</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get villages in a district</div>
                </li>

                <li class="endpoint-item">
                    <span class="method get">GET</span>
                    <span class="endpoint-path">/village/get/{code}</span>
                    <span class="auth-badge no-auth">Publik</span>
                    <div class="description">Get village details by code</div>
                </li>
            </ul>
        </div>
    </div>
</body>

</html>
