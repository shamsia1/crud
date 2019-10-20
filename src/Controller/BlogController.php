<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Article;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\Common\Persistence\ObjectManager;

class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog")
     */
    public function index() /** pour nous afficher la list de l'article*/
    {

    	$repo = $this->getDoctrine()->getRepository(Article::class);
    	
    	$articles = $repo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' =>$articles
        ]);
    }


    /**
    *@Route("/", name="home")
    */

    public function home(){
    	return $this->render('blog/home.html.twig');
    }


    /**
    *@Route("/blog/new", name="blog_create")
    *@Route("/blog/{id}/edit", name="blog_edit")
    *@Route("/blog/{id}/delete", name="blog_delete")
    */
    
    public function form(Article $article = null, Request $request,ObjectManager $manager){
    	if (!$article) {
    			$article = new Article();
    	}
    

    	$form = $this->createFormBuilder($article)
            ->add('title')
            ->add('content')
            ->add('image')
           
            ->getForm();

            $form->handleRequest($request);
            if($form->isSubmitted() && $form->isValid()){

            	if(!$article->getId()){

            		$article->setCreatedAt(new \DateTime());
           
            	}


            	$manager->persist($article);

            	$manager->flush();


            	return $this->redirectToRoute('blog_show', [
            		'id' =>$article->getId()
            	]);
            }

    	return $this->render('blog/create.html.twig', [
    		'formArticle' => $form->createView(),
    		'editMode' => $article->getId() !== null
    	]);
    }



    /**
    *@Route("/blog/{id}", name="blog_show")
    */
    public function show($id)
    {

    	$repo = $this->getDoctrine()->getRepository(Article::class);
    	$article = $repo->find($id); 

    	return $this->render('blog/show.html.twig', [
    		'article' =>$article
    	]);
    }





}



