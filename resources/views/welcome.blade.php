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
            $publicEndpoints = [
                'Data Wilayah' => [
                    [
                        'method' => 'GET',
                        'path' => '/get/province',
                        'description' => 'Mendapatkan data provinsi.',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'type' => 'string',
                                'description' => 'Pencarian nama provinsi (partial match)',
                                'required' => false,
                                'default' => 'null',
                            ],
                            [
                                'name' => 'paginate',
                                'type' => 'boolean',
                                'description' => 'Mengaktifkan paginasi (true/false)',
                                'required' => false,
                                'default' => 'false',
                            ],
                            [
                                'name' => 'perPage',
                                'type' => 'integer',
                                'description' => 'Jumlah item per halaman jika paginasi diaktifkan',
                                'required' => false,
                                'default' => '10',
                            ],
                        ],
                        'example' => '/get/province',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/province/{code}',
                        'description' => 'Mendapatkan detail provinsi berdasarkan kode provinsi',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' => 'Kode unik provinsi (misal: 21 untuk Jawa Timur)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/province/21',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/province/{code}/city',
                        'description' => 'Mendapatkan kota dalam provinsi berdasarkan kode provinsi',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' => 'Kode unik provinsi (misal: 21 untuk Jawa Timur)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/province/21/city',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/city',
                        'description' => 'Mendapatkan data kota atau kabupaten',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'type' => 'string',
                                'description' => 'Pencarian nama kota/kabupaten (partial match)',
                                'required' => false,
                                'default' => 'null',
                            ],
                            [
                                'name' => 'paginate',
                                'type' => 'boolean',
                                'description' => 'Mengaktifkan paginasi (true/false)',
                                'required' => false,
                                'default' => 'false',
                            ],
                            [
                                'name' => 'perPage',
                                'type' => 'integer',
                                'description' => 'Jumlah item per halaman jika paginasi diaktifkan',
                                'required' => false,
                                'default' => '10',
                            ],
                        ],
                        'example' => '/get/city',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/city/{code}',
                        'description' => 'Mendapatkan detail kota berdasarkan kode kota',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' => 'Kode unik kota/kabupaten (misal: 2172 untuk Kota Tanjung Pinang)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/city/2172',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/city/{code}/district',
                        'description' => 'Mendapatkan kecamatan dalam kota berdasarkan kode kota',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' => 'Kode unik kota/kabupaten (misal: 2172 untuk Kota Tanjung Pinang)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/city/2172/district',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/district',
                        'description' => 'Mendapatkan data kecamatan',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'type' => 'string',
                                'description' => 'Pencarian nama kecamatan (partial match)',
                                'required' => false,
                                'default' => 'null',
                            ],
                            [
                                'name' => 'paginate',
                                'type' => 'boolean',
                                'description' => 'Mengaktifkan paginasi (true/false)',
                                'required' => false,
                                'default' => 'false',
                            ],
                            [
                                'name' => 'perPage',
                                'type' => 'integer',
                                'description' => 'Jumlah item per halaman jika paginasi diaktifkan',
                                'required' => false,
                                'default' => '10',
                            ],
                        ],
                        'example' => '/get/district',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/district/{code}',
                        'description' => 'Mendapatkan detail kecamatan berdasarkan kode kecamatan',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' =>
                                    'Kode unik kecamatan (misal: 217202 untuk Kecamatan Tanjung Pinang Timur)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/district/217202',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/district/{code}/village',
                        'description' => 'Mendapatkan kelurahan dalam kecamatan berdasarkan kode kecamatan',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' =>
                                    'Kode unik kecamatan (misal: 217202 untuk Kecamatan Tanjung Pinang Timur)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/district/217202/village',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/village',
                        'description' => 'Mendapatkan data kelurahan',
                        'parameters' => [
                            [
                                'name' => 'q',
                                'type' => 'string',
                                'description' => 'Pencarian nama kelurahan (partial match)',
                                'required' => false,
                                'default' => 'null',
                            ],
                            [
                                'name' => 'paginate',
                                'type' => 'boolean',
                                'description' => 'Mengaktifkan paginasi (true/false)',
                                'required' => false,
                                'default' => 'false',
                            ],
                            [
                                'name' => 'perPage',
                                'type' => 'integer',
                                'description' => 'Jumlah item per halaman jika paginasi diaktifkan',
                                'required' => false,
                                'default' => '10',
                            ],
                        ],
                        'example' => '/get/village',
                    ],
                    [
                        'method' => 'GET',
                        'path' => '/get/village/{code}',
                        'description' => 'Mendapatkan detail kelurahan berdasarkan kode kelurahan',
                        'parameters' => [
                            [
                                'name' => 'code',
                                'type' => 'string',
                                'description' =>
                                    'Kode unik kelurahan (misal: 2172021001 untuk Kelurahan Melayu Kota Piring)',
                                'required' => true,
                            ],
                        ],
                        'example' => '/get/village/2172021001',
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


        <div class="bg-white rounded-lg shadow-md p-4 md:p-6">
            <h3 class="text-lg md:text-xl font-semibold mb-4 flex items-center">
                <span class="mr-2">🔓</span> Public Endpoints
            </h3>
            <ul class="space-y-3">
                @foreach ($publicEndpoints as $section => $endpoints)
                    <li class="font-bold text-base md:text-lg text-gray-700 pt-3 md:pt-4">{{ $section }}</li>
                    @foreach ($endpoints as $endpoint)
                        <li class="border border-gray-200 rounded-lg p-4 hover:bg-gray-100 transition-all">
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center gap-3">
                                    <span
                                        class="px-3 py-1 {{ $colors[strtolower($endpoint['method'])] }} text-white text-sm font-medium rounded">
                                        {{ $endpoint['method'] }}
                                    </span>
                                    <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                        {{ $endpoint['path'] }}
                                    </code>
                                </div>
                                <div class="text-gray-600 font-medium">
                                    {{ $endpoint['description'] }}
                                </div>

                                @if (isset($endpoint['parameters']))
                                    <div>
                                        <h4 class="font-semibold text-gray-700 mb-2">Parameters:</h4>
                                        <ul class="space-y-2">
                                            @foreach ($endpoint['parameters'] as $param)
                                                <li class="bg-gray-50 p-3 rounded border border-gray-200">
                                                    <div class="flex items-center justify-between">
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-sm text-gray-500">
                                                                {{ $param['name'] }}
                                                            </span> -
                                                            <span
                                                                class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                                                {{ $param['type'] }}
                                                            </span>
                                                        </div>
                                                        @if ($param['required'])
                                                            <span
                                                                class="text-xs bg-red-100 text-red-700 px-2 py-1 rounded-full font-semibold">
                                                                Required
                                                            </span>
                                                        @else
                                                            <span
                                                                class="text-xs bg-green-100 text-green-700 px-2 py-1 rounded-full font-semibold">
                                                                Optional
                                                            </span>
                                                        @endif
                                                    </div>
                                                    <div class="mt-1 text-gray-600 text-sm">
                                                        {{ $param['description'] }}
                                                    </div>
                                                    @if (isset($param['default']))
                                                        <div class="mt-1 text-gray-500 text-sm">
                                                            <strong>Default:</strong> {{ $param['default'] }}
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">Example:</span>
                                    <a href="{{ route('home') . $endpoint['example'] }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800">
                                        <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">
                                            {{ $endpoint['example'] }}
                                        </code>
                                    </a>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</body>

</html>
