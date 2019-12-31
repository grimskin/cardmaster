<?php


namespace App\Controller;


use App\Model\CardData;
use App\Service\DataLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    private $dataLoader;

    public function __construct(DataLoader $dataLoader)
    {
        $this->dataLoader = $dataLoader;
    }

    public function cardsList()
    {
        $sortedData = array_map(function(CardData $item) {
            return $item->getName();
        }, $this->dataLoader->getAllData());
        asort($sortedData);

        return new JsonResponse(array_values($sortedData));
    }
}
