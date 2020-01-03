<?php


namespace App\Controller;


use App\Factory\ConditionFactory;
use App\Model\CardData;
use App\Service\DataLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    private $dataLoader;

    private $conditionFactory;

    public function __construct(
        DataLoader $dataLoader,
        ConditionFactory $conditionFactory
    ) {
        $this->dataLoader = $dataLoader;
        $this->conditionFactory = $conditionFactory;
    }

    public function cardsList()
    {
        $sortedData = array_map(function(CardData $item) {
            return $item->getName();
        }, $this->dataLoader->getAllData());
        asort($sortedData);

        return new JsonResponse(array_values($sortedData));
    }

    public function conditionsList()
    {
        return new JsonResponse($this->conditionFactory->getRegisteredConditions());
    }
}
