<?php

namespace App\Controller;

use App\Entity\Blogs;
use App\Form\BlogsType;
use App\Repository\BlogsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blogs")
 * Class BlogsController
 * @package App\Controller
 */
class BlogsController extends AbstractController
{
    /**
     * @Route("/", name="blogs_index", methods={"GET"})
     */
    public function index(BlogsRepository $blogsRepository): Response
    {
        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="blogs_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $blog = new Blogs();
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($blog);
            $entityManager->flush();

            return $this->redirectToRoute('blogs_index');
        }

        return $this->render('blogs/new.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="blogs_show", methods={"GET"})
     */
    public function show(Request $request, Blogs $blog): Response
    {

        return $this->render('blogs/show.html.twig', [
            'blog' => $blog,
        ]);
    }

    /**
     * @Route("/article/{id}/edit", name="blogs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Blogs $blog): Response
    {
        $form = $this->createForm(BlogsType::class, $blog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('blogs_index', [
                'id' => $blog->getId(),
            ]);
        }

        return $this->render('blogs/edit.html.twig', [
            'blog' => $blog,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/{id}", name="blogs_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Blogs $blog): Response
    {
        if ($this->isCsrfTokenValid('delete'.$blog->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($blog);
            $entityManager->flush();
        }

        return $this->redirectToRoute('blogs_index');
    }
}
