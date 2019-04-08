<?php
/**
 * Created by PhpStorm.
 * User: Administrateur
 * Date: 04/04/2019
 * Time: 13:22
 */

namespace App\Controller;


use App\Entity\Blogs;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class BlogsUserController extends AbstractController


{

    /**
     * @Route("/blogs/article" , name="article")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showArticle(){
        $repotArticle=$this->getDoctrine()->getRepository(Blogs::class);
        $article = $repotArticle->findAll();

        //$commentaire = new Commentaire();

        $repotCommentaire=$this->getDoctrine()->getRepository(Commentaire::class);
        $commentaires = $repotCommentaire->findBy(['blogs'=>$article]);






        return $this->render('Blogs/show_blog.html.twig', ['articles'=>$article,'commentaires'=>$commentaires]);
    }

    /**
     * @Route("/blogs/blogs/{idArticle}", name="mainarticleId" )
     * @param Request $request
     * @param $idArticle
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showBlogs (Request $request,$idArticle){
    $com = new Commentaire();
    $repoArticles = $this->getDoctrine()->getRepository(Blogs::class);
    $article = $repoArticles->find($idArticle);
    $repoCommentaires = $this->getDoctrine()->getRepository(Commentaire::class);
    $commentaires = $repoCommentaires->findBy(['blogs'=>$idArticle]);
    $form = $this->createForm(CommentaireType::class, $com);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()){

        $com->setUsers($this->getUser());
        $com->setBlogs($article);
        $em = $this->getDoctrine()->getManager();
        $em->persist($com);
        $em->flush();

        return $this->redirectToRoute("mainarticleId",['idArticle'=>$idArticle]);

    }


    return $this->render('blogs/articleId.html.twig',['article'=>$article, 'commentaires'=>$commentaires,'form'=>$form->createView()]);


    }
}