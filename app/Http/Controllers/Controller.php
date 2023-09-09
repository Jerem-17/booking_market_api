<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    // protected static  USERS_IMG_PATH = "users";

    // protected static const BOUTIQUES_IMG_PATH = "boutiques";

    // protected static const IMAGES_DISK = "images";

    // protected static const DEFAULT_USER_IMG = self::IMAGES_DISK . '/' . self::USERS_IMG_PATH . "/default.jpg";

    // protected static const DEFAULT_BOUTIQUE_IMG = self::IMAGES_DISK . '/' . self::BOUTIQUES_IMG_PATH . "/default.jpg";
}
