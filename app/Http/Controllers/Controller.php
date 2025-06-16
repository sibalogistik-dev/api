<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'hrd',
        'finance',
        'absensi',
        'logistik',
        'mobile',
    ];
}
