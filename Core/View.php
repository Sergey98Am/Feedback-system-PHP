<?php

namespace Core;

use App\Auth;

class View
{
//    public static function rennder($view, $args = [])
//    {
//        extract($args, EXTR_SKIP);
//
//        $file = "../App/Views/$view";  // relative to Core directory
//
//        if (is_readable($file)) {
//            require $file;
//        } else {
//            throw new \Exception("$file not found");
//        }
//    }

    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
            $twig->addFunction(new \Twig\TwigFunction('asset', function ($asset) {
                // implement whatever logic you need to determine the asset path

                return sprintf('../assets/%s', ltrim($asset, '/'));
            }));
        }

        echo $twig->render($template, $args);
    }
}
