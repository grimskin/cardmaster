<?php


namespace App\Controller;


use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Factory\ScenarioFactory;
use App\Model\CardData;
use App\Model\DeckDefinition;
use App\Service\DataLoader;
use App\Service\DeckFetcher;
use App\Service\SimulationSetUpHelper;
use App\Service\StatsCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    private DataLoader $dataLoader;

    private SimulationSetUpHelper $setUpHelper;

    private ConditionFactory $conditionFactory;

    private ScenarioFactory $scenarioFactory;

    private CardsFactory $cardsFactory;

    private StatsCollector $statsCollector;

    private DeckFetcher $deckFetcher;

    public function __construct(
        DataLoader $dataLoader,
        SimulationSetUpHelper $setUpHelper,
        ConditionFactory $conditionFactory,
        ScenarioFactory $scenarioFactory,
        CardsFactory $cardsFactory,
        StatsCollector $statsCollector,
        DeckFetcher $deckFetcher
    ) {
        $this->dataLoader = $dataLoader;
        $this->setUpHelper = $setUpHelper;
        $this->conditionFactory = $conditionFactory;
        $this->scenarioFactory = $scenarioFactory;
        $this->cardsFactory = $cardsFactory;
        $this->statsCollector = $statsCollector;
        $this->deckFetcher = $deckFetcher;
    }

    public function cardsList(): Response
    {
        $sortedData = array_map(function(CardData $item) {
            return $item->getName();
        }, $this->dataLoader->getAllData());
        asort($sortedData);

        return new JsonResponse(array_values($sortedData));
    }

    public function cardInfo(string $cardName): Response
    {
        $card = $this->cardsFactory->getCard($cardName);

        return new JsonResponse($card);
    }

    public function conditionsList(): Response
    {
        return new JsonResponse($this->conditionFactory->getRegisteredConditions());
    }

    public function scenariosList(): Response
    {
        return new JsonResponse($this->scenarioFactory->getRegisteredScenarios());
    }

    public function simulation(Request $request): Response
    {
        // TODO: Refactor
        $data = json_decode($request->getContent(), true);
        $scenarioName = $data['scenario']['scenario'];
        $conditions = $data['conditions'];
        $cardData = $data['deck'];

        $this->setUpHelper->configureScenario($this->statsCollector, $scenarioName);

        foreach ($conditions as $condition) {
            $this->setUpHelper->configureCondition($this->statsCollector, $condition['name'], $condition['param']);
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

        return new JsonResponse($experimentResult);
    }

    public function fetchDeck(Request $request): Response
    {
        return new JsonResponse($this->deckFetcher->fetchDeck($request->get('deck_url', '')));
    }
}
