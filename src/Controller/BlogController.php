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
     * @Route("/blog", name="blog")
     * @return Response A response instance
     */
    public function index(): Response
    {
        $articles = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findAll();

        if (!$articles) {
            throw $this->createNotFoundException('No article found in article\'s table.');
        }

        return $this->render('/index.html.twig',['articles' => $articles]);
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

        $slug = ucwords(str_replace("-"," ",$slug));


        $article = $this->getDoctrine()
            ->getRepository(Article::class)
            ->findOneBy(['title' => mb_strtolower($slug)]);

        if (!$article) {
            throw $this->createNotFoundException(
                'No article with '.$slug.' title, found in article\'s table.'
            );
        }

        return $this->render(
            '/show.html.twig', ['article' => $article,'slug' => $slug,]);
    }

    // Fonction permétant de retourné la page category.html.twig et demander la récupération des informations nécessaires dans la BDD.

    /**
     * @Route("/category/{categoryName}", methods={"GET"}, name="category")
     *
     * @return Response A response instance
     */
    public function showByCategory(string $categoryName) : Response
    {
        // Si $categoryName n'exite pas alors retourné la phrase 'No find category in category's table.'
        if (!$categoryName) {
            throw $this
                ->createNotFoundException('No find category in category\'s table.');
        }

        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneByName($categoryName);
        $limit=3;

        // Récupérer les articles par rapport à la catégorie récupérrer précédement.

            $articles = $this->getDoctrine()
                ->getRepository(Article::class)
                ->findByCategory($category,['id'=>'DESC'],$limit);
            return $this->render(
                '/category.html.twig', ['articles' => $articles,'category' => $category,]
            );
    }
}