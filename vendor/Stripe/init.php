<?php
// Simple autoloader for Stripe
spl_autoload_register(function ($class) {
    if (strpos($class, 'Stripe\\') === 0) {
        $class = str_replace('Stripe\\', '', $class);
        $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require $file;
        }
    }
});
?> 