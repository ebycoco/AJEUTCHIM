<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppOpenExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    } 

    public function getFunctions(): array
    {
        return [
            new TwigFunction('set_active_route1', [$this, 'doSomething']),
        ];
    }

    public function doSomething(string $route,?string $openClass ='menu-open'):string
    {
        $currentRoute = $this->requestStack->getCurrentRequest()->attributes->get('_route'); 
        return $currentRoute == $route ? $openClass : '';
    }
}
