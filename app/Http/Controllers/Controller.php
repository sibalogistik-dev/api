<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'finance',
        'absensi',
        'logistik',
        'mobile',
    ];
}
