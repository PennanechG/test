<?php
// src/Controller/BlogController.php
namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * @Route("/blog", name="blog_index")
     */
    public function index()
    {
        return $this->render('/index.html.twig', ['owner' => 'Thomas']);
    }

    /**
     * @Route("/blog/show/{slug<[a-z0-9\-]+>?article-sans-titre}", methods={"GET"}, name="show")
     */
    public function show($slug)
    {
        $cleanSlug = ucwords(str_replace("-"," ",$slug));
        return $this->render('/show.html.twig', ['slug' => $cleanSlug]);
    }
}