
<?php

namespace App\TwigExtension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('price', [$this, 'formatPrice']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('area', [$this, 'calculateArea']),
        ];
    }

    public function formatPrice($number, $decimals = 0, $decPoint = '.', $thousandsSep = ',')
    {
        $price = number_format($number, $decimals, $decPoint, $thousandsSep);
        $price = '$'.$price;

        return $price;
    }

    public function calculateArea(int $width, int $length)
    {
        return $width * $length;
    }


    public function breadcrumb($separator = ' &raquo; ', $home = 'Home') {
        $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));

        // This will build our "base URL" ... Also accounts for HTTPS :)
        $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';

        // Initialize a temporary array with our breadcrumbs. (starting with our home page, which I'm assuming will be the base URL)
        $breadcrumbs = array("<a href=\"$base\">".$home."</a>");

        // Find out the index for the last value in our path array
        //$last = end(array_keys($path));

        // Build the rest of the breadcrumbs
        foreach ($path AS $x => $crumb) {
            // Our "title" is the text that will be displayed (strip out .php and turn '_' into a space)
            $title = ucwords(str_replace(Array('.php', '_'), Array('', ' '), $crumb));

            // If we are not on the last index, then display an <a> tag
            if ($x != $last)
                $breadcrumbs[] = "<a href=\"".$base.$crumb."\">".$title."</a>";
            // Otherwise, just display the title (minus)
            else
                $breadcrumbs[] = $title;
        }

        // Build our temporary array (pieces of bread) into one big string :)
        return implode($separator, $breadcrumbs);
    }
}
