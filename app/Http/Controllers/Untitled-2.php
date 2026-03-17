<?php

if (!function_exists('generate_captcha')) {

    function generate_captcha($length = 6)
    {
        // Generate random code
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';
        $code = '';

        for ($i = 0; $i < $length; $i++) {
            $code .= $characters[random_int(0, strlen($characters) - 1)];
        }

        // Store in session
        session(['captcha_code' => $code]);

        // Create simple SVG image
        $svg = '
        <svg xmlns="http://www.w3.org/2000/svg" width="160" height="50">
            <rect width="100%" height="100%" fill="#f2f2f2"/>
            <text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle"
                  font-size="24" font-family="Arial" fill="#000">
                '.$code.'
            </text>
        </svg>';

        return response($svg)->header('Content-Type', 'image/svg+xml');
    }
}
