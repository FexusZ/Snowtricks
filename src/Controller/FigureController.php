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
     * @Route("/figure/edit/{id}", name="figure.edit", methods="POST||GET")
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
            $this->addFlash('success', 'Figure modifié');

            return $this->redirectToRoute('index');
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
            $upload_file = $this->getParameter('upload_directory');

            foreach ($request->files->get('figure')['images']['image'] as $file) {
                $upload = new Image;

                $file_name = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($upload_file, $file_name);

                $upload->setImage($file_name);

                $upload->setIdFigure($figure->getId());
                $em = $this->getDoctrine()->getManager();
                $em->persist($upload);
                $em->flush();
            }
            foreach ($request->request->get('figure')['videos']['video'] as $file) {
                if (strpos('https://www.youtube.com') !== false) {

                } elseif (strpos('https://www.youtube.com') !== false) {

                } else {

                }
                $upload = new Video;

                $upload->setVideo($file);

                $upload->setIdFigure($figure->getId());
                $em = $this->getDoctrine()->getManager();
                $em->persist($upload);
                $em->flush();
            }

            $this->addFlash('success', 'Figure Créé');


            return $this->redirectToRoute('index');
        }

        return $this->render('pages/figures/create.html.twig', ['current_menu' => 'figures.create', 'form' => $form->createView()]);
    }

    /**
     *  @Route("/figure/edit/{id}", name="figure.delete", methods="DELETE")
     * @param Figures $figure
     * @return mixed
     */
    public function delete(Figures $figure, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $figure->getId(), $request->get('_token'))) {

            $image = $this->getDoctrine()->getRepository(Image::class);
            $video = $this->getDoctrine()->getRepository(Video::class);

            $row['row_image'] = $image->findBy(['id_figure' => $figure->getId()]);
            $row['row_video'] = $video->findBy(['id_figure' => $figure->getId()]);

            foreach ($row as $type => $row_type) {
                foreach ($row_type as $file) {
                    if ($type == 'row_image' && file_exists('uploads/'.$file->getImage()))
                        unlink ('uploads/'.$file->getImage());
                    if ($type == 'row_video' && file_exists('uploads/'.$file->getVideo()))
                        unlink ('uploads/'.$file->getVideo());
                }
            }


            $em = $this->getDoctrine()->getManager();

            $Image = $em->getPartialReference('App\entity\Image', array('id_figure' => $figure->getId()));
            $em->remove($Image);
            $em->flush();

            $Video = $em->getPartialReference('App\entity\Video', array('id_figure' => $figure->getId()));
            $em->remove($Video);
            $em->flush();

            $em->remove($figure);
            $em->flush();
            $this->addFlash('success', 'Figure supprimé');
        }

        return $this->redirectToRoute('index');
    }
}