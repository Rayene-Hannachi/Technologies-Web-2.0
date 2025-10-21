<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Form\BookType;
use App\Form\BookEditType;
use App\Entity\Book;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\AuthorRepository;


final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }
        #[Route('/addB', name: 'add_books')]

    public function addBook(ManagerRegistry $em, Request $request, AuthorRepository $authorRepo): Response
{
    $book = new Book();
    $form = $this->createForm(BookType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $em->getManager();
        $authorId = $book->getAuthor()->getId();
        $author = $authorRepo->find($authorId);

        if ($author) {
            $book->setAuthor($author);
            $author->setnb_Books($author->getnb_Books() + 1);
            $entityManager->persist($author);
            $entityManager->persist($book);
            $entityManager->flush();
        }

        return $this->redirectToRoute('get_books');
    }

    return $this->render('book/add.html.twig', [
        'f' => $form->createView(),
    ]);
} 
#[Route('/updateB/{id}', name: 'app_update')]
public function updateBook(int $id, ManagerRegistry $em, Request $request, AuthorRepository $authorRepo): Response
{
    $entityManager = $em->getManager();
    $book = $entityManager->getRepository(Book::class)->find($id);
    $oldAuthor = $book->getAuthor();
    $form = $this->createForm(BookEditType::class, $book);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $newAuthor = $book->getAuthor();

        if ($oldAuthor && $newAuthor && $oldAuthor->getId() !== $newAuthor->getId()) {
            $oldAuthor->setnb_Books(max(0, $oldAuthor->getnb_Books() - 1));
            $newAuthor->setnb_Books($newAuthor->getnb_Books() + 1);

            $entityManager->persist($oldAuthor);
            $entityManager->persist($newAuthor);
        }

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('get_books');
    }

    return $this->render('book/update.html.twig', [
        'f' => $form->createView(),
    ]);
}

    #[Route('/getB', name: 'get_books')]
    public function getAll(BookRepository $bookRepo): Response
    {
        $books = $bookRepo->findAll();

        return $this->render('book/showbooks.html.twig', [
            'books' => $books,
        ]);
    }
    #[Route('/show/{id}', name: 'app_details')]
    public function getOne(BookRepository $bookRepo, int $id): Response
    {
        $book = $bookRepo->find($id);

        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }

        return $this->render('book/bookdetail.html.twig', [
            'book' => $book,
        ]);
    }
    #[Route('/deleteB/{id}', name: 'app_delete')]
    public function DeleteBook(ManagerRegistry $em, BookRepository $bookRepo, AuthorRepository $AuthorRepo,$id): Response
    {
        $book = $bookRepo->find($id);
        $em->getManager()->remove($book);
        $authorId = $book->getAuthor()->getId();
        $author = $AuthorRepo->find($authorId);
        $author->setnb_Books($author->getnb_Books() - 1);
        $em->getManager()->flush();
        return $this->redirectToRoute('get_books');
    }
}
