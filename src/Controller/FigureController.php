<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

class FigureController extends AbstractController
{
    /**
     * @var Environment
     */
    private $twig;
    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * @Route("figures/liste", name="figures.listes")
     * @return Response
     */

    public function index(): Response
    {
        return new Response($this->twig->render('pages/figures/listes.html.twig', ['current_menu' => 'figures.listes']));
    }
}