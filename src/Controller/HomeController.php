<?php


namespace App\Controller;


use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

use \App\Repository\ClientRepository;
use \App\Entity\Client as ClientEntity;

use \App\Entity\Figures;
use \App\Entity\Image;
use \App\Entity\Video;
use \App\Form\FigureType;

class HomeController extends AbstractController
{
    /**
     * @var ClientRepository
     */
    private $client;
    public function __construct(ClientRepository $client)
    {
        $this->client = $client;
    }

    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $figures = $this->getDoctrine()->getRepository(Figures::class);
        $image = $this->getDoctrine()->getRepository(Image::class);
        $query_figure = $figures->findAll();
        $tab_query = array();
        foreach ($query_figure as $row_figure) {
            $row_image = $image->findOneBy(['id_figure' => $row_figure->getId()]);

            $tab_query[$row_figure->getId()] = array('figure' => $row_figure, 'image' => $row_image);
        }

        return $this->render('pages/index.html.twig', ['current_menu' => 'index', 'tab_query' => $tab_query]);
    }
}