<?php


namespace App\Controller;


use App\Domain\TestChamber;
use App\Factory\CardsFactory;
use App\Factory\ConditionFactory;
use App\Model\CardData;
use App\Model\DeckDefinition;
use App\Scenarios\ScenarioConfig;
use App\Service\DataLoader;
use App\Service\DeckFetcher;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends AbstractController
{
    private DataLoader $dataLoader;

    private ConditionFactory $conditionFactory;

    private CardsFactory $cardsFactory;

    private DeckFetcher $deckFetcher;

    private TestChamber $chamber;

    public function __construct(
        DataLoader $dataLoader,
        ConditionFactory $conditionFactory,
        CardsFactory $cardsFactory,
        DeckFetcher $deckFetcher,
        TestChamber $chamber
    ) {
        $this->dataLoader = $dataLoader;
        $this->conditionFactory = $conditionFactory;
        $this->cardsFactory = $cardsFactory;
        $this->deckFetcher = $deckFetcher;
        $this->chamber = $chamber;
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

    /**
     * @deprecated has no use from now on
     *
     * @return Response
     */
    public function scenariosList(): Response
    {
        return new JsonResponse([['name' =>'general-scenario', 'title'=>'general-scenario']]);
    }

    public function simulation(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);
        $conditions = $data['conditions'];
        $cardData = $data['deck'];

        foreach ($conditions as $condition) {
            $this->chamber->addCondition($this->conditionFactory->getCondition(
                $condition['name'], [$condition['param']]
            ));
        }

        // TODO: Refactor deck composition
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

        $config = new ScenarioConfig();
        $config->setPassCount(10000);

        $this->chamber->setScenarioConfig($config);
        $this->chamber->setDeck($deck);

        $experimentResult = $this->chamber->runSimulation();

        return new JsonResponse($experimentResult);
    }

    public function fetchDeck(Request $request): Response
    {
        return new JsonResponse($this->deckFetcher->fetchDeck($request->get('deck_url', '')));
    }
}
