<?php
namespace Kna\BEMBundle\Tests\App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function index(): Response
    {
        return $this->render('index.html.twig');
    }
}