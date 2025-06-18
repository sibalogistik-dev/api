<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'hrd app',
        'finance app',
        'absensi app',
        'logistik app',
        'mobile app',
    ];
}
