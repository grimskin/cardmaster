<?php


namespace App\Controller;


use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\CardData;
use App\Service\DataLoader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{
    private $dataLoader;

    private $conditionFactory;

    private $scenarioFactory;

    public function __construct(
        DataLoader $dataLoader,
        ConditionFactory $conditionFactory,
        ScenarioFactory $scenarioFactory
    ) {
        $this->dataLoader = $dataLoader;
        $this->conditionFactory = $conditionFactory;
        $this->scenarioFactory = $scenarioFactory;
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

    public function scenariosList()
    {
        return new JsonResponse($this->scenarioFactory->getRegisteredScenarios());
    }
}
