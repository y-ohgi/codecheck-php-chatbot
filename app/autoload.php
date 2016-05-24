<?php

require __DIR__ . '/../vendor/autoload.php';
spl_autoload_register(function ($class) {
    static $classes = [];
    if (empty($classes)) {
        $classes = [
            'sprint\\bot' => '/Bot.php',
        ];
    }
    $cn = strtolower($class);
    if (isset($classes[$cn])) {
        require(__DIR__ . $classes[$cn]);
    }
}, true, false);
