<?php

namespace Core;

use App\Auth;
use Core\Model;

class View
{
    public static function rennder($view, $args = [])
    {
        extract($args, EXTR_SKIP);

        $file = "../App/Views/$view";  // relative to Core directory

        if (is_readable($file)) {
            require $file;
        } else {
            throw new \Exception("$file not found");
        }
    }

    public static function renderTemplate($template, $args = [])
    {
        static $twig = null;

        if ($twig === null) {
            $loader = new \Twig\Loader\FilesystemLoader('../App/Views');
            $twig = new \Twig\Environment($loader);
            $twig->addGlobal('current_user', Auth::getUser());
            if (Session::exists('errors')) {
                $errors = Session::get('errors');
                $twig->addGlobal('errors', $errors);
                Session::delete('errors');
            }
            $twig->addFunction(new \Twig\TwigFunction('asset', function ($asset) {
                // implement whatever logic you need to determine the asset path
                return sprintf('../assets/%s', ltrim($asset, '/'));
            }));
        }

        echo $twig->render($template, $args);
    }
}
