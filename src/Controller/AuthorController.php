<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Author;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showAuthor/{name}', name: 'show_author')]
    public function showAuthor(string $name): Response
    {
    return $this->render('author/show.html.twig', [
        'name' => $name,
    ]);
    }
    #[Route('/listAuthors', name: 'list_author')]
    public function listAuthor(): Response
    {
        $authors = array(
array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>'victor.hugo@gmail.com', 'nb_books' => 100),
array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>'william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    return $this->render('author/list.html.twig', [
        'authors' => $authors,
    ]);
    }
    #[Route('/author/{id}', name: 'author_details')]
    public function authorDetails($id): Response
    {
        $authors = array(
array('id' => 1, 'picture' => '/images/Victor-Hugo.jpg','username' => 'Victor Hugo', 'email' =>'victor.hugo@gmail.com', 'nb_books' => 100),
array('id' => 2, 'picture' => '/images/william-shakespeare.jpg','username' => ' William Shakespeare', 'email' =>'william.shakespeare@gmail.com', 'nb_books' => 200 ),
array('id' => 3, 'picture' => '/images/Taha_Hussein.jpg','username' => 'Taha Hussein', 'email' =>'taha.hussein@gmail.com', 'nb_books' => 300),
        );
    $author = null;
    foreach ($authors as $a) {
        if ($a['id'] == $id) {
            $author = $a;
            break;
        }
    }
    return $this->render('author/showAuthor.html.twig', [
        'author' => $author,
    ]);
    }
    #[Route('/get', name: 'get_authors')]
    public function getAll(AuthorRepository $authorRepo): Response
    {
        $authors = $authorRepo->findAll();
        
    return $this->render('author/showauthors.html.twig', [
        'authors' => $authors,
    ]);
    }
    #[Route('/add', name: 'add_authors')]
    public function addAuthor(ManagerRegistry $em): Response
    {
        $author1 = new Author();
        $author1->setUsername('author1');
        $author1->setEmail('author1@gmail.com');

        $author2 = new Author();
        $author2->setUsername('author2');
        $author2->setEmail('author2@gmail.com');
        
        $em->getManager()->persist($author1);
        $em->getManager()->persist($author2);
        $em->getManager()->flush();
        return  new Response("added successfully ");

    }
    #[Route('/delete/{id}', name: 'app_delete')]
    public function DeleteAuthor(ManagerRegistry $em,AuthorRepository $AuthorRepo,$id): Response
    {
        $auth = $AuthorRepo->find($id);
        $em->getManager()->remove($auth);
        $em->getManager()->flush();
        //return(new Response("deleted successfully"));
        return $this->redirectToRoute('get_authors');
    }



}
