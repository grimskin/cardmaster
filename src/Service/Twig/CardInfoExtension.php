<?php


namespace App\Service\Twig;


use Twig\Extension\AbstractExtension;
use Twig\Markup;
use Twig\TwigFunction;

class CardInfoExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('card_info', [$this, 'cardInfo']),
        ];
    }

    public function cardInfo(string $cardName): string
    {
        return $this->canonizeCardName($cardName);
    }

    private function canonizeCardName(string $cardName): string
    {
        $canonizedName = strtolower($cardName);
        $canonizedName = str_replace(
            [',', ' ', 'ü'],
            ['', '-', 'u'],
            $canonizedName
        );

        return new Markup(
            '<a class="card-info" href="/images/cards/'.$canonizedName.'.png" target="_blank">'.$cardName.'<img src="/images/cards/'.$canonizedName.'.png" /></a>'
            , 'UTF-8');
    }
}
