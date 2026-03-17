<?php

use Illuminate\Support\Facades\Response;

if (!function_exists('generate_captcha')) {

    function generate_captcha($width = 150, $height = 50, $length = 6)
    {
        // 1. Generate random captcha code
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // 2. Store in session
        session(['captcha_code' => $code]);

        // 3. Create image
        $image = imagecreatetruecolor($width, $height);

        // Colors
        $bgColor   = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $lineColor = imagecolorallocate($image, 180, 180, 180);
        $dotColor  = imagecolorallocate($image, 100, 100, 100);

        // Background
        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Random noise lines
        for ($i = 0; $i < 5; $i++) {
            imageline(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                random_int(0, $width),
                random_int(0, $height),
                $lineColor
            );
        }

        // Random noise dots
        for ($i = 0; $i < 100; $i++) {
            imagesetpixel(
                $image,
                random_int(0, $width),
                random_int(0, $height),
                $dotColor
            );
        }

        // Center text
        $fontSize = 5;
        $x = ($width - imagefontwidth($fontSize) * strlen($code)) / 2;
        $y = ($height - imagefontheight($fontSize)) / 2;

        imagestring($image, $fontSize, $x, $y, $code, $textColor);

        // Capture output
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return Response::make($imageData, 200, [
            'Content-Type' => 'image/png'
        ]);
    }
}
