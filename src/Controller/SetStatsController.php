<?php


namespace App\Controller;


use App\Service\DataLoader;
use App\Service\Set\StatsCollector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class SetStatsController extends AbstractController
{
    const DEFAULT_SET = 'AFR';

    private StatsCollector $collector;
    private DataLoader $dataLoader;

    public function __construct(StatsCollector $collector, DataLoader $dataLoader)
    {
        $this->collector = $collector;
        $this->dataLoader = $dataLoader;
    }

    public function index(): Response
    {
        return new RedirectResponse(
            $this->generateUrl('set_view', ['set' => self::DEFAULT_SET])
        );
    }

    public function setStats(string $set): Response
    {
        if (!$this->isValidSet($set)) {
            return new RedirectResponse($this->generateUrl('set_view', ['set' => self::DEFAULT_SET]));
        }

        $this->collector->addCards($this->dataLoader->loadSet($set));

        return $this->render('set/stats.html.twig', [
            'stats' => $this->collector,
        ]);
    }

    private function isValidSet(string $set): bool
    {
        $validSets = [
            'MID', 'AFR', 'STX', 'KHM', 'ZNR', 'M21', 'IKO', 'THB', 'ELD',
        ];

        return in_array(strtoupper($set), $validSets);
    }
}
