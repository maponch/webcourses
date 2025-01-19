<?php

namespace App\Controller;

use App\Repository\NewsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class NewsController extends AbstractController
{
    #[Route('/news', name: 'app_news')]
    public function index(NewsRepository $repository, Request $request, PaginatorInterface $paginator): Response
    {
        $news = $repository->findAll();
        $pagination = $paginator->paginate($news, $request->query->getInt('page', 1),
            4
        );

        return $this->render('news/index.html.twig', [
            'news' => $pagination,
        ]);
    }
}
