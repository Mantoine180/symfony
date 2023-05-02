<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\MatiereExtensionRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MatiereExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_minute_by_matiere', [MatiereExtensionRuntime::class, 'getMinutesByMatiere']),
        ];
    }
}
