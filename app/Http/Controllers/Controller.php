<?php

namespace App\Http\Controllers;

abstract class Controller
{
    public $allowedDevices = [
        'app.access.karyawan',
        'app.access.hrd',
        'app.access.finance',
        'app.access.logistik',
        'app.access.marketing',
    ];
}
