<?php


namespace App\Controller;


use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\CardData;
use App\Model\DeckDefinition;
use App\Service\DataLoader;
use App\Service\DeckFetcher;
use App\Service\StatsCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    private $dataLoader;

    private $conditionFactory;

    private $scenarioFactory;

    private $cardsFactory;

    private $statsCollector;

    private $deckFetcher;

    public function __construct(
        DataLoader $dataLoader,
        ConditionFactory $conditionFactory,
        ScenarioFactory $scenarioFactory,
        CardsFactory $cardsFactory,
        StatsCollector $statsCollector,
        DeckFetcher $deckFetcher
    ) {
        $this->dataLoader = $dataLoader;
        $this->conditionFactory = $conditionFactory;
        $this->scenarioFactory = $scenarioFactory;
        $this->cardsFactory = $cardsFactory;
        $this->statsCollector = $statsCollector;
        $this->deckFetcher = $deckFetcher;
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

    public function simulation(Request $request)
    {
        // TODO: Refactor
        $data = json_decode($request->getContent(), true);
        $scenario = $data['scenario']['scenario'];
        $conditions = $data['conditions'];
        $cardData = $data['deck'];

        $this->statsCollector->setScenario(
            $this->scenarioFactory->getScenario($scenario)
        );

        foreach ($conditions as $condition) {
            $this->statsCollector->addCondition(
                $this->conditionFactory->getCondition($condition['name'], [$condition['param']])
            );
        }

        $cardsCount = array_reduce($cardData, function ($carry, $item) {
            return $carry + $item['amount'];
        }, 0);

        $stubsCount = 60 - $cardsCount;

        $deck = new DeckDefinition();

        foreach ($cardData as $cardDatum) {
            $deck->addCards(
                $this->cardsFactory->getCard($cardDatum['name']),
                $cardDatum['amount']
            );
        }

        if ($stubsCount) {
            $deck->addCards($this->cardsFactory->getStub(), $stubsCount);
        }

        $this->statsCollector->setDeck($deck);
        $this->statsCollector->setPassCount(10000);

        $experimentResult = $this->statsCollector->runSimulation();
        $this->statsCollector->getSuccessCount();

        return new JsonResponse([
            'success' => $experimentResult->getSuccessCount(),
            'total' => $experimentResult->getPassCount(),
        ]);
    }

    public function fetchDeck(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $deckUrl = $data['deck_url'] ?? 'https://www.mtggoldfish.com/deck/arena_download/3013464';

        return new JsonResponse($this->deckFetcher->fetchDeck($deckUrl));
    }
}
