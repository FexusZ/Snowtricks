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
use \App\Form\FigureType;


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
                $file_name = md5(uniqid()). '.'.$file->guessExtension();
                $file->move($upload_file, $file_name);
                $image->setImage($file_name);
                $figure->addImage($image);
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

            return $this->redirectToRoute('index');
        }

        return $this->render('pages/figures/edit.html.twig', ['current_menu' => 'figures.listes', 'form' => $form->createView(), 'figure' => $figure]);
    }

    /**
     * @param Figures $figure
     * @Route("/figure/show/{id<[0-9]+>}", name="figure.show")
     * @return Response
     */
    public function show(Figures $figure): Response
    {
        return $this->render('pages/figures/show.html.twig', ['current_menu' => 'figures.listes', 'figure' => $figure]);
    }

    /**
     * @Route("/figure/create", name="figure.create")
     * @param Request $request
     * @return Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $figure = new Figures;
        $form = $this->createForm(FigureType::class, $figure);
        $clientRepo = $em->getRepository('App:Client');
        $client = $clientRepo->find(1);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $figure->setIdClient($client);
            $figure->updateTimestamps();


            $em->persist($figure);
            $upload_file = $this->getParameter('upload_directory');

            foreach ($request->files->get('figure')['images']['image'] as $file) {
                $upload = new Image;

                $file_name = md5(uniqid()) . '.' . $file->guessExtension();
                $file->move($upload_file, $file_name);
                $upload->setImage($file_name);
                dump($upload);
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
    public function delete(Figures $figure, Request $request)
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
}