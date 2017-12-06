<?php

use Appel\Honeypot\Facades\Honeypot;

if (!function_exists('honeypot'))
{
    function honeypot($name, $time)
    {
        return Honeypot::make($name, $time);
    }
}
