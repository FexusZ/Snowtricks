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
     * @param Figures $figure
     * @Route("/figure/edit/{id}", name="figure.edit")
     * @param Request $request
     * @return Response
     */
    public function edit(Figures $figure, Request $request): Response
    {
        dump($figure->getImage());
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
     * @param Figures $figure
     * @Route("/figure/show/{id}", name="figure.show")
     * @return Response
     */
    public function show(Figures $figure): Response
    {
        $image = $this->getDoctrine()->getRepository(Image::class);
        $video = $this->getDoctrine()->getRepository(Video::class);

        $row_image = $image->findBy(['id_figure' => $figure->getId()]);
        $row_video = $video->findBy(['id_figure' => $figure->getId()]);

        $tab_query[$figure->getId()] = array('figure' => $figure, 'image' => $row_image, 'video' => $row_video);


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
            dump($request->files->get('figure')['images']['image'], $request->files->get('figure')['videos']['video']);
            $files['image'] = $request->files->get('figure')['images']['image'];
            $files['video'] = $request->files->get('figure')['videos']['video'];
            $upload_file = $this->getParameter('upload_directory');
            foreach ($files as $file_type => $file_content) {
                foreach ($file_content as $file) {
                    if ($file_type === 'image')
                        $upload = new Image;
                    elseif ($file_type === 'video')
                        $upload = new Video;

                    $file_name = md5(uniqid()) . '.' . $file->guessExtension();
                    $file->move($upload_file, $file_name);

                    if ($file_type === 'image')
                        $upload->setImage($file_name);
                    elseif ($file_type === 'video')
                        $upload->setVideo($file_name);

                    $upload->setIdFigure($figure->getId());
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($upload);
                    $em->flush();
                }
            }

            return $this->redirectToRoute('figures.listes');
        }

        return $this->render('pages/figures/create.html.twig', ['current_menu' => 'figures.create', 'form' => $form->createView()]);
    }
}