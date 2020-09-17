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
use Symfony\Component\Security\Core\User\UserInterface;

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
        $query_figure = $figures->findAll();

        return $this->render('pages/index.html.twig', ['current_menu' => 'index', 'figures' => $query_figure]);
    }

    /**
     * @Route("/fixture", name="fixture")
     * @return Response
     */
    public function fixture(): Response
    {
        $client = new ClientEntity();
        $client->setUsername('John Doe')
        ->setEmail('johndoe@gmail.com')
        // mdp = test
        ->setPassword(' $argon2id$v=19$m=65536,t=4,p=1$TDhJYmlqcy5OQWppazNoRg$raFEzp5vdih+DL+9ocequVUBV7NsuHzq7iLmX1lIf2s');

        $em = $this->getDoctrine()->getManager();
        $em->persist($client);
        $em->flush();

        return $this->render('pages/index.html.twig', ['current_menu' => 'index', 'tab_query' => []]);
    }
}