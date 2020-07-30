<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

use \App\Entity\Figures;
use \App\Entity\Image;
use \App\Entity\Video;
use \App\Form\FigureType;

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
        $figures = $this->getDoctrine()->getRepository(Figures::class);
        $image = $this->getDoctrine()->getRepository(Image::class);
        $query_figure = $figures->findAll();
        foreach ($query_figure as $row_figure) {
            $row_image = $image->findOneBy(['id_figure' => $row_figure->getId()]);

            $tab_query[$row_figure->getId()] = array('figure' => $row_figure, 'image' => $row_image);
        }

        dump($tab_query);
        return new Response($this->twig->render('pages/figures/listes.html.twig', ['current_menu' => 'figures.listes', 'tab_query' => $tab_query]));
    }

    /**
     * @Route("/figure/edit/{id}", name="figure.edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Request $request): Response
    {
        $figure = new Figures;
        $image = new Image;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($figure);
            $em->flush();
            $files = $request->files->get('figure')['images']['image'];
            $upload_file = $this->getParameter('upload_directory');
            foreach ($files as $file) {
                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $image->setImage($file_name);
                $image->setIdFigure($figure->getId());
                $em->persist($image);
                $em->flush();
            }
            return $this->redirectToRoute('figures.listes');
        }
        return $this->render('pages/figures/edit.html.twig', ['current_menu' => 'figures.listes', 'form' => $form->createView()]);
    }

    /**
     * @Route("/figure/show/{id}", name="figure.show")
     * @param Request $request
     * @return Response
     */
    public function show(Figures $figure): Response
    {
        $image = $this->getDoctrine()->getRepository(Image::class);

        $row_image = $image->findBy(['id_figure' => $figure->getId()]);

        $tab_query[$figure->getId()] = array('figure' => $figure, 'image' => $row_image);


        if (isset($tab_query))
            dump($tab_query);
        return $this->render('pages/figures/show.html.twig', ['current_menu' => 'figures.listes', 'tab_query' => $tab_query]);
    }

    /**
     * @Route("/figure/create", name="figure.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $figure = new Figures;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($figure);
            $em->flush();
            $files = $request->files->get('figure')['images']['image'];
            $upload_file = $this->getParameter('upload_directory');
            foreach ($files as $file) {
                $image = new Image;

                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $image->setImage($file_name);
                $image->setIdFigure($figure->getId());
                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();
            }

            $files = $request->files->get('figure')['videos']['video'];
            $upload_file = $this->getParameter('upload_directory');
            foreach ($files as $file) {
                $video = new Video;

                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $video->setVideo($file_name);
                $video->setIdFigure($figure->getId());
                $em = $this->getDoctrine()->getManager();
                $em->persist($video);
                $em->flush();
            }

            return $this->redirectToRoute('figures.listes');
        }

        return $this->render('pages/figures/create.html.twig', ['current_menu' => 'figures.create', 'form' => $form->createView()]);
    }
}