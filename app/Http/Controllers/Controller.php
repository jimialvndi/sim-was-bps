<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
// Baris ini penting agar controller bisa baca middleware:
use Illuminate\Routing\Controller as BaseController; 

abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}