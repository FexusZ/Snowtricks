<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use \App\Entity\Client as ClientEntity;
use \App\Entity\Figures;
use \App\Entity\Image;
use \App\Entity\Video;
use \App\Entity\Commentaire;
use \App\Form\FigureType;
use \App\Form\ImageType;
use \App\Form\VideoType;
use \App\Form\CommentaireType;


class FigureController extends AbstractController
{
    /**
     * @param Figures $figure
     * @Route("/figure/edit/{id<[0-9]+>}", name="figure.edit", methods="POST||GET")
     * @param Request $request
     * @return Response
     */
    public function edit(Figures $figure, Request $request): Response
    {
        $form = $this->createForm(FigureType::class, $figure);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $upload_file = $this->getParameter('upload_directory');

            foreach ($request->files->get('figure')['images']['image'] as $file) {
                $image = new Image;
                if ($image->checkImage($file)) {
                    $file_name = md5(uniqid()). '.'.$file->guessExtension();
                    $file->move($upload_file, $file_name);
                    $image->setImage($file_name);
                    $figure->addImage($image);
                }
            }
            foreach ($request->request->get('figure')['videos']['video'] as $file) {
                $video = new Video;
                if (strpos($file, 'https://www.youtube.com/embed') !== false || strpos($file, 'https://www.dailymotion.com/embed') !== false) {
                    $video->setVideo($file);
                    $figure->addVideo($video);
                } elseif ($file !== '') {
                    $this->addFlash('error', 'La vidéo : "'.$file. '", ne conviens pas.');
                }
            }

            $figure->updateTimestamps();

            $em->persist($figure);
            $em->flush();
            $this->addFlash('success', 'Figure modifié');

            return $this->redirectToRoute('figure.edit', ['id' => $figure->getId()]);

        }

        return $this->render('pages/figures/edit.html.twig', ['current_menu' => 'figures.listes', 'form' => $form->createView(), 'figure' => $figure]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/details/{id<[0-9]+>}", name="figure.show")
     * @return Response
     */
    public function show(Figures $figure, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $commentaire = new Commentaire;
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setFigure($figure)
            ->setClient($this->getUser())
            ->updateTimestamps()
            ;
            $em->persist($commentaire);
            $em->flush();
            $this->addFlash('info', 'Commentaire ajouté!');
            return $this->redirectToRoute('figure.show', [
                'id' => $figure->getId()
            ]);
        }

        return $this->render('pages/figures/show.html.twig', ['current_menu' => 'figures.listes', 'figure' => $figure, 'form' => $form->createView()]);
    }

    /**
     * @Route("/figure/create", name="figure.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $figure = new Figures;
        $form = $this->createForm(FigureType::class, $figure);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $figure->setIdClient($this->getUser());
            $figure->updateTimestamps();


            $em->persist($figure);
            $upload_file = $this->getParameter('upload_directory');
            foreach ($request->files->get('figure')['images']['image'] as $file) {
                $upload = new Image;

                $file_name = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($upload_file, $file_name);
                $upload->setImage($file_name);
                $figure->addImage($upload);
            }
            
            foreach ($request->request->get('figure')['videos']['video'] as $file) {
                $upload = new Video;
                if (strpos($file, 'https://www.youtube.com/embed') !== false || strpos($file, 'https://www.dailymotion.com/embed') !== false) {
                    $upload->setVideo($file);
                    $figure->addVideo($upload);
                } elseif ($file !== '') {
                    $this->addFlash('error', 'La vidéo : "'.$file. '", ne conviens pas.');
                }
            }
            $em->persist($figure);
            $em->flush();

            $this->addFlash('success', 'Figure Créé');

            return $this->redirectToRoute('index');
        }

        return $this->render('pages/figures/create.html.twig', ['current_menu' => 'figures.create', 'form' => $form->createView()]);
    }

    /**
     *  @Route("/figure/edit/{id<[0-9]+>}", name="figure.delete", methods="DELETE")
     * @param Figures $figure
     * @return mixed
     */
    public function delete(Figures $figure, Request $request): Response
    {
        if ($this->isCsrfTokenValid('delete' . $figure->getId(), $request->get('_token'))) {


            foreach ($figure->getImages() as $file) {
                if (file_exists('uploads/'.$file->getImage()))
                    unlink ('uploads/'.$file->getImage());
            }

            $em = $this->getDoctrine()->getManager();

            $em->remove($figure);
            $em->flush();
            $this->addFlash('info', 'Figure supprimé');
        }

        return $this->redirectToRoute('index');
    }

    /**
     * @param Figures $figure
     * @Route("/figure/image/update/{id<[0-9]+>}", name="figure.image.update", methods="POST||GET")
     * @return Response
     */
    public function imageUpdate(Request $request, Image $image): Response
    {
        if (($this->isCsrfTokenValid('update_image' . $image->getId(), $request->get('_token')))) {
            $upload_file = $this->getParameter('upload_directory');
            $file_name = $request->request->get('old_image');

            if (file_exists('uploads/'.$file_name))
                unlink ('uploads/'.$file_name);

            $request->files->get('image')->move($upload_file, $file_name);
            $this->addFlash('info', 'image modifié');

        }
        return $this->redirectToRoute('figure.edit', ['id' => $image->getIdFigure()->getId()]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/image/update/{id<[0-9]+>}", name="figure.image.delete", methods="DELETE")
     * @return Response
     */
    public function imageDelete(Request $request, Image $image): Response
    {
        if (($this->isCsrfTokenValid('delete_image' . $image->getId(), $request->get('_token')))) {
            $em = $this->getDoctrine()->getManager();
            if (file_exists('uploads/'.$image->getImage()))
                unlink ('uploads/'.$image->getImage());

            $figure = $image->getIdFigure();

            if ($figure->getFeaturedImage() === $image->getId()) {
                $figure->setFeaturedImage(0);
                $em = $this->getDoctrine()->getManager();
                $em->persist($figure);
                $this->addFlash('info', 'Image a la une supprimé!');
            }

            $em->remove($image);
            $em->flush();
            $this->addFlash('info', 'vidéo supprimé');
        }
        return $this->redirectToRoute('figure.edit', ['id' => $image->getIdFigure()->getId()]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/video/update/{id<[0-9]+>}", name="figure.video.update", methods="POST||GET")
     * @return Response
     */
    public function videoUpdate(Request $request, Video $video): Response
    {
        if (($this->isCsrfTokenValid('update_video' . $video->getId(), $request->get('_token')))) {
            $video->setVideo($request->request->get('video'));
            $em = $this->getDoctrine()->getManager();

            $em->persist($video);
            $em->flush();
            $this->addFlash('info', 'vidéo modifié');

        }
        return $this->redirectToRoute('figure.edit', ['id' => $video->getIdFigure()->getId()]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/video/update/{id<[0-9]+>}", name="figure.video.delete", methods="DELETE")
     * @return Response
     */
    public function videoDelete(Request $request, Video $video): Response
    {
        if (($this->isCsrfTokenValid('delete_video' . $video->getId(), $request->get('_token')))) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($video);
            $em->flush();
            $this->addFlash('info', 'vidéo supprimé');
        }
        return $this->redirectToRoute('figure.edit', ['id' => $video->getIdFigure()->getId()]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/image/featured/{id<[0-9]+>}", name="figure.image.featured", methods="POST||GET")
     * @return Response
     */
    public function imagefeatured(Request $request, Image $image): Response
    { 
        if (($this->isCsrfTokenValid('featured_image' . $image->getId(), $request->get('_token')))) {
            $figure = $image->getIdFigure();
            $figure->setFeaturedImage($image->getId());
            $em = $this->getDoctrine()->getManager();
            $em->persist($figure);
            $em->flush();
            $this->addFlash('info', 'Image a la une modifié!');
        }
        return $this->redirectToRoute('figure.edit', ['id' => $image->getIdFigure()->getId()]);
    }
}