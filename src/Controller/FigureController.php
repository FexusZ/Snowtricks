<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Symfony\Component\Routing\Annotation\Route;

use \App\Entity\Figures;
use \App\Entity\Image;
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

    public function index(Request $request): Response
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
                $em = $this->getDoctrine()->getManager();
                $em->persist($image);
                $em->flush();
            }
            return $this->redirectToRoute('figures.listes');
        }
        return new Response($this->twig->render('pages/figures/listes.html.twig', ['current_menu' => 'figures.listes', 'form' => $form->createView()]));
    }

    /**
     * @param Figure $figure
     * @Route("/figure/edit/{id}, name='figure.edit')
     * @return mixed
     */
    public function edit(Figure $figure) {
        return this->render('admin/figure/edit.html.twig', compact('figure'));
    }
}