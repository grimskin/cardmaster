<?php


namespace App\Controller;


use App\Service\Helper\JsonResultsReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ArticlesController extends AbstractController
{
    private JsonResultsReader $resultsReader;

    public function __construct(JsonResultsReader $resultsReader)
    {
        $this->resultsReader = $resultsReader;
    }

    public function article($url): Response
    {
        if ($url !== strtolower($url)) {
            $this->redirectToRoute('articles', ['url' => strtolower($url)]);
        }

        $articleTitles = [
            'can-i-cast-dmu' => 'Can I Cast (Dominaria United Limited version)',
        ];
        $params['article_title'] = $articleTitles[$url] ?? '';

        if ($url === 'can-i-cast-dmu') {
            $params = array_merge($params, $this->canICastDmu());
        }

        dump($params);

        return $this->render('articles/'.$url.'.html.twig', $params);
    }

    private function canICastDmu() : array
    {
        return [
            'monoInMono' => $this->resultsReader->getResults('mono-in-mono', [], true),
            'monoInMonoPlus' => $this->resultsReader->getResults('mono-in-mono-plus', [], true),
        ];
    }
}
