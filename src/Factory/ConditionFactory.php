<?php


namespace App\Factory;


use App\Conditions\CanCast;
use App\Conditions\ConditionInterface;
use App\Helper\CanCastCache;
use Exception;
use Symfony\Contracts\Service\Attribute\Required;

class ConditionFactory
{
    /**
     * @var ConditionInterface[]
     */
    private array $conditions = [];
    private ?CanCastCache $canCastCache = null;

    private CardsFactory $cardsFactory;

    public function __construct(CardsFactory $cardsFactory)
    {
        $this->cardsFactory = $cardsFactory;
    }

    public function registerCondition(string $conditionClassName): void
    {
        $condition = new $conditionClassName;

        if ($condition instanceof ConditionInterface) {
            $this->conditions[$condition->getName()] = $condition;
        }
    }

    public function getRegisteredConditions(): array
    {
        return array_map(function (ConditionInterface $condition) {
            return [
                'name' => $condition->getName(),
                'title' => $condition->getReadableName(),
            ];
        }, $this->conditions);
    }

    #[Required]
    public function setCacheHandler(CanCastCache $cache): void
    {
        $this->canCastCache = $cache;
    }

    public function getCondition(string $conditionName, array $params = [], $turn = 1): ?ConditionInterface
    {
        /** @var ConditionInterface|CanCast $result */
        $result = $this->conditions[$conditionName] ?? null;

        if (!$result) {
            throw new Exception('No condition named ' . $conditionName . ' found');
        }

        $result->addParams($params);
        $result->setTurn($turn);
        $result->setCardsFactory($this->cardsFactory);

        if ($result->getName() === 'can-cast' && $this->canCastCache !== null) {
            $result->setCacheHandler($this->canCastCache);
        }

        return $result;
    }
}
