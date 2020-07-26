<?php


namespace App\Controller;


use http\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;

use \App\Repository\ClientRepository;
use \App\Entity\Client as ClientEntity;
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
        /* Création d'un client
        $client = new ClientEntity();
        $client->setUserName('Fexus')
            ->setFirstName('Jean-Sébastien')
            ->setLastName('Neuhart')
            ->setEmail('jseb.1999@outlook.fr')
            ->setPassword('FexusTest')
        ;
        $this->em = $this->getDoctrine()->getManager();
        $this->em->persist($client);
        $this->em->flush();
        */
        $request = $this->client->connexion('Fexus', 'FexusTest');
        dump($request);
        return $this->render('pages/index.html.twig', ['current_menu' => 'index']);
    }
}