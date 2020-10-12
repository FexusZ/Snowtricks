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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


use Symfony\Component\HttpFoundation\File\File;

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
}