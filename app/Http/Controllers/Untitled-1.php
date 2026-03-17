<?php

if (!function_exists('generate_captcha')) {

    function generate_captcha($width = 150, $height = 50)
    {
        // Generate random code
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789';
        $code = '';

        for ($i = 0; $i < 6; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }

        // Store in session
        session(['captcha_code' => $code]);

        // Create image
        $image = imagecreatetruecolor($width, $height);

        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);
        $lineColor = imagecolorallocate($image, 180, 180, 180);

        imagefilledrectangle($image, 0, 0, $width, $height, $bgColor);

        // Noise lines
        for ($i = 0; $i < 5; $i++) {
            imageline(
                $image,
                rand(0, $width),
                rand(0, $height),
                rand(0, $width),
                rand(0, $height),
                $lineColor
            );
        }

        // Center text
        $fontSize = 5;
        $x = ($width - imagefontwidth($fontSize) * strlen($code)) / 2;
        $y = ($height - imagefontheight($fontSize)) / 2;

        imagestring($image, $fontSize, $x, $y, $code, $textColor);

        // Output image
        ob_start();
        imagepng($image);
        $imageData = ob_get_clean();
        imagedestroy($image);

        return response($imageData)->header('Content-Type', 'image/png');
    }
}
