<?php


namespace App\Service\Twig;


use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PercentageExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('experiment_percentage', [$this, 'experimentPercentage']),
        ];
    }

    public function experimentPercentage(int $success, int $total, int $decimalCount = 1): string
    {
        return number_format(100 * $success / $total, $decimalCount) . '%';
    }
}
