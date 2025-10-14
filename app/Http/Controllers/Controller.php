<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'karyawan app',
        'hrd app',
        'marketing app',
        'finance app',
        'vendor app',
        'logistik app',
        'mobile app',
    ];
}
