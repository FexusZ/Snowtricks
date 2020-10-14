<?php


namespace App\Controller;

use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use \App\Entity\Figures;

/**
 * Class HomeController
 * @package App\Controller
 */
class HomeController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $figures = $this->getDoctrine()->getRepository(Figures::class);
        $query_figure = $figures->findAll();

        return $this->render('pages/index.html.twig', ['current_menu' => 'index', 'figures' => $query_figure]);
    }
}