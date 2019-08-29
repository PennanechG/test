<?php
// src/Controller/BlogController.php
namespace App\Controller;


use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class BlogController extends AbstractController
{
    /**
     * Show all row from article's entity
     *
     * @Route("/", name="index")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException(
                'No article found in article\'s table.'
            );
        }

        return $this->render(
            'blog/index.html.twig',
            ['articles' => $articles]
        );
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $slug The slugger
     *
     * @Route("/blog/show/{slug<[a-z0-9\-]+>?article-sans-titre}", methods={"GET"}, name="show")
     *
     * @return Response A response instance
     */
    public function show($slug)
    {
        if (!$slug) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an article in article\'s table.');
        }

        $cleanSlug = ucwords(str_replace("-"," ",$slug));


        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            'blog/show.html.twig', ['article' => $article,'slug' => $slug,]);
    }

    /**
     * Getting a article with a formatted slug for title
     *
     * @param string $categoryName The slugger
     *
     * @Route("/blog/show/{$categoryName}", methods={"GET"}, name="show_category")
     *
     * @return Response A response instance
     */
    public function showByCategory($categoryName)
    {
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No slug has been sent to find an category in category\'s table.');
        }

        $categoryName = ucwords(str_replace("-"," ",$categoryName));


        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName(mb_strtolower($categoryName));

        if (!$category) {

            $articles = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findOneByCategory($category);
            return $this->render(
                'blog/category.html.twig', ['articles' => $articles,'category' => $category,]);
        }

    }
}