<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class SetStatsController extends AbstractController
{
    public function setStats(string $set): Response
    {
        return $this->render('set/stats.html.twig', [
            'set' => $set,
        ]);
    }
}
