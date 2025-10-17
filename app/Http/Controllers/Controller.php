<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'login karyawan',
        'login hrd',
        'login marketing',
        'login finance',
        'login vendor',
        'login logistik',
        'login mobile',
    ];
}
