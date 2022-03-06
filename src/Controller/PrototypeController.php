<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class PrototypeController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('proto/index.html.twig');
    }
}
