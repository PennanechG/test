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

        return $this->render('/blog/index.html.twig',['articles' => $articles]);
    }

    // Fonction permétant de retourné la page category.html.twig et demander la récupération des informations nécessaires dans la BDD.

    /**
     * @Route("/category/{name}", methods={"GET"}, name="show_category")
     *
     * @param Category $category
     *
     * @return Response A response instance
     */
    public function showByCategory(Category $category) : Response
    {
        // Si $categoryName n'exite pas alors retourné la phrase 'No find category in category's table.'
        if (!$category) {
            throw $this
                ->createNotFoundException('No find category in category\'s table.');
        }

        // Récupérer les articles par rapport à la catégorie récupérrer précédement.

            $articles = $category->getArticles();

        return $this->render(
                '/blog/category.html.twig', ['articles' => $articles,'category' => $category,]
            );
    }


}