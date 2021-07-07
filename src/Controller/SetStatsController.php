<?php


namespace App\Controller;


use App\Service\DataLoader;
use App\Service\Set\StatsCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SetStatsController extends AbstractController
{
    private StatsCollector $collector;
    private DataLoader $dataLoader;

    public function __construct(StatsCollector $collector, DataLoader $dataLoader)
    {
        $this->collector = $collector;
        $this->dataLoader = $dataLoader;
    }

    public function setStats(string $set): Response
    {
        $this->collector->addCards($this->dataLoader->loadSet($set));

        return $this->render('set/stats.html.twig', [
            'set' => $set,
            'cards' => $this->collector->getCards(),
        ]);
    }
}
