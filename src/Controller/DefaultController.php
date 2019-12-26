<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    public function index()
    {
        return new Response(
            <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="build/app.css">
    <title>--</title>
</head>
<body>
    <div id="root"></div>
    
    <script src="build/runtime.js"></script>
    <script src="build/vendors~app.js"></script>
    <script src="build/app.js"></script>
</body>
</html>
HTML
        );
    }
}
