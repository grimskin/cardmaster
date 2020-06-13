<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        $links = $this->getAssetsLinks();

        $html = '<!DOCTYPE html><html lang="en"><head>'."\r\n";
        foreach ($links['entrypoints']['app']['css'] as $cssLink) {
            $html .= '<link rel="stylesheet" href="'.$cssLink.'">'."\r\n";
        }
        $html .= '<title>--</title></head><body><div id="root"></div>'."\r\n";
        foreach ($links['entrypoints']['app']['js'] as $jsLink) {
            $html .= '<script src="'.$jsLink.'"></script>'."\r\n";
        }
        $html .= '<!-- '.getcwd().' -->';
        $html .= '</body></html>';

        return new Response($html);
    }

    private function getAssetsLinks()
    {
        return json_decode(file_get_contents($this->getParameter('kernel.project_dir') . '/public/build/entrypoints.json'), true);
    }
}
