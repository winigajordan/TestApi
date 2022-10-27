<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use App\Repository\CategorieRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/perso/')]
class ApiPersoController extends AbstractController
{
    

    #[Route('articles', name: 'get_article', methods:'GET')]
    public function getArticle(ArticleRepository $articleRepository){
        $articles = $articleRepository->findAll();
        $data = [];
        foreach ($articles as $article) {
            $data [] = [
                'id'=>$article->getId(),
                'libelle'=>$article->getLibelle(),
                'description'=>$article->getDescription(),
                'createdAt'=>$article->getCreateAt()
            ];
        }
        return $this->json($data, status:200);
    }

    #[Route('articles', name:'add_article', methods:'POST')]
    public function addArticle(Request $request, CategorieRepository $catRipo, EntityManagerInterface $em){
        $data = $request->request;
        $art = (new Article)
        ->setLibelle($data->get('libelle'))
        ->setDescription($data->get('description'))
        ->setCreateAt(new DateTimeImmutable())
        ->setCat($catRipo->find($data->get('cat')));
        
        $em->persist($art);
        $em->flush();

        return $this->json('insertion effectuée', status:200);

    }
}
