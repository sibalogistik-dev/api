<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo/siba cargo.png') }}">
    <title>API Documentation</title>
    {{-- <script src="https://cdn.tailwindcss.com"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

</head>

<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header Section -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo/siba cargo.png') }}" alt="Siba Cargo Logo"
                class="max-w-[200px] mx-auto h-auto">
            <h1 class="text-3xl font-bold mt-4 text-gray-800">API Documentation</h1>
            <p class="text-gray-600 mt-2">
                Anda berada di subdomain
                (<strong>{{ str_replace(['http://', 'https://'], '', route('home')) }}</strong>)
            </p>
        </div>
        @php
            $protectedEndpoints = [
                'Provinsi' => [
                    [
                        'method' => 'GET',
                        'path' => '/provinsi',
                        'description' => 'Mendapatkan semua data provinsi',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/provinsi',
                        'description' => 'Menambahkan data provinsi baru',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/provinsi/{code}',
                        'description' => 'Mendapatkan detail provinsi berdasarkan kode',
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/provinsi/{code}',
                        'description' => 'Memperbarui data provinsi berdasarkan kode',
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/provinsi/{code}',
                        'description' => 'Menghapus data provinsi berdasarkan kode',
                    ],
                ],
                'Kota dan Kabupaten' => [
                    [
                        'method' => 'GET',
                        'path' => '/kotakab',
                        'description' => 'Mendapatkan semua data kota/kabupaten',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/kotakab',
                        'description' => 'Menambahkan data kota/kabupaten baru',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/kotakab/{code}',
                        'description' => 'Mendapatkan detail kota/kabupaten berdasarkan kode',
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/kotakab/{code}',
                        'description' => 'Memperbarui data kota/kabupaten berdasarkan kode',
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/kotakab/{code}',
                        'description' => 'Menghapus data kota/kabupaten berdasarkan kode',
                    ],
                ],
                'Kecamatan' => [
                    [
                        'method' => 'GET',
                        'path' => '/kecamatan',
                        'description' => 'Mendapatkan semua data kecamatan',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/kecamatan',
                        'description' => 'Menambahkan data kecamatan baru',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/kecamatan/{code}',
                        'description' => 'Mendapatkan detail kecamatan berdasarkan kode',
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/kecamatan/{code}',
                        'description' => 'Memperbarui data kecamatan berdasarkan kode',
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/kecamatan/{code}',
                        'description' => 'Menghapus data kecamatan berdasarkan kode',
                    ],
                ],
                'Kelurahan' => [
                    [
                        'method' => 'GET',
                        'path' => '/kelurahan',
                        'description' => 'Mendapatkan semua data kelurahan',
                    ],
                    [
                        'method' => 'POST',
                        'path' => '/kelurahan',
                        'description' => 'Menambahkan data kecamatan baru',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/kelurahan/{code}',
                        'description' => 'Mendapatkan detail kelurahan berdasarkan kode',
                    ],
                    [
                        'method' => 'PUT',
                        'path' => '/kelurahan/{code}',
                        'description' => 'Memperbarui data kelurahan berdasarkan kode',
                    ],
                    [
                        'method' => 'DELETE',
                        'path' => '/kelurahan/{code}',
                        'description' => 'Menghapus data kelurahan berdasarkan kode',
                    ],
                ],
            ];

            $publicEndpoints = [
                'Autentikasi' => [
                    [
                        'method' => 'POST',
                        'path' => '/login',
                        'description' => 'Autentikasi pengguna dan mendapatkan token',
                    ],
                ],
                'Data Wilayah' => [
                    [
                        'method' => 'GET',
                        'path' => '/province/get',
                        'description' => 'Mendapatkan data provinsi dari Indonesia',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/province/get/{code}',
                        'description' => 'Mendapatkan detail provinsi berdasarkan kode',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/province/get/{code}/city',
                        'description' => 'Mendapatkan kota dalam provinsi',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/city/get/{code}',
                        'description' => 'Mendapatkan detail kota berdasarkan kode',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/city/get/{code}/district',
                        'description' => 'Mendapatkan kecamatan dalam kota',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/district/get/{code}',
                        'description' => 'Mendapatkan detail kecamatan berdasarkan kode',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/district/get/{code}/village',
                        'description' => 'Mendapatkan kelurahan dalam kecamatan',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/village/get/{code}',
                        'description' => 'Mendapatkan detail kelurahan berdasarkan kode',
                    ],
                ],
            ];

            $colors = [
                'get' => 'bg-green-500',
                'post' => 'bg-indigo-500',
                'put' => 'bg-yellow-500',
                'delete' => 'bg-red-500',
            ];
        @endphp

        <div class="bg-white rounded-lg shadow-md p-4 md:p-6 mb-6 md:mb-8">
            <h3 class="text-lg md:text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🔒</span> Protected Endpoints
            </h3>
            <ul class="space-y-3">
                @foreach ($protectedEndpoints as $section => $endpoints)
                    <li class="font-bold text-base md:text-lg text-gray-700 pt-3 md:pt-4">{{ $section }}</li>
                    @foreach ($endpoints as $endpoint)
                        <li class="border-b border-gray-100 pb-3 transition-all hover:bg-gray-50 rounded-md p-2">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        class="px-2 py-1 {{ $colors[strtolower($endpoint['method'])] }} text-white text-xs md:text-sm rounded">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <code
                                        class="text-xs md:text-sm break-all sm:break-normal">{{ $endpoint['path'] }}</code>
                                </div>
                                <span
                                    class="px-2 py-1 bg-blue-500 text-white text-xs rounded-full self-start sm:self-auto">
                                    Butuh Autentikasi
                                </span>
                            </div>
                            <div class="text-gray-600 text-xs md:text-sm mt-2">{{ $endpoint['description'] }}</div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>

        <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🔓</span> Public Endpoints
            </h3>
            <ul class="space-y-3">
                @foreach ($publicEndpoints as $section => $endpoints)
                    <li class="font-bold text-base md:text-lg text-gray-700 pt-3 md:pt-4">{{ $section }}</li>
                    @foreach ($endpoints as $endpoint)
                        <li class="border-b border-gray-100 pb-3 transition-all hover:bg-gray-50 rounded-md p-2">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-2">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        class="px-2 py-1 {{ $colors[strtolower($endpoint['method'])] }} text-white text-xs md:text-sm rounded">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <code
                                        class="text-xs md:text-sm break-all sm:break-normal">{{ $endpoint['path'] }}</code>
                                </div>
                                <span
                                    class="px-2 py-1 bg-gray-500 text-white text-xs rounded-full self-start sm:self-auto">
                                    Publik
                                </span>
                            </div>
                            <div class="text-gray-600 text-xs md:text-sm mt-2">{{ $endpoint['description'] }}</div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</body>

</html>
