<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Response;

class CaptchaController extends Controller
{
    public function generate()
    {
        $num1 = rand(2, 5);
        $num2 = rand(2, 5);
        $result = $num1 + $num2;

        Session::put('captcha_result', $result);

        $image = imagecreate(120, 40);
        $background = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 255);

        imagestring($image, 5, 10, 10, "$num1 + $num2 = ?", $textColor);

        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();

        imagedestroy($image);

        return response($imageData, 200)->header('Content-Type', 'image/png');
    }
}
