<?php

namespace App\Controller;

use App\Form\PostType;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class PostController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    #[Route('/posts', name: 'show_posts')]
    public function index(): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $posts = $this->entityManager->getRepository(Post::class)->findAll();  
        return $this->render('post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/post', name: 'create_post')]
    public function createPost(Request $req): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }
        
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('message', 'Post created successfully');
            return $this->redirectToRoute('show_posts');
        }
        return $this->render('post/createUpdate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/update-post/{id}', name: 'update_post')]
    public function updatePost(Request $req, $id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($post);
            $this->entityManager->flush();
            $this->addFlash('message', 'Post with ID '.$id.' updated successfully');
            return $this->redirectToRoute('show_posts');
        }
        return $this->render('post/createUpdate.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete-post/{id}', name: 'delete_post')]
    public function deletePost($id): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_index');
        }
        $post = $this->entityManager->getRepository(Post::class)->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
        $this->addFlash('message', 'Post with ID '.$id.' deleted successfully');
        return $this->redirectToRoute('show_posts');
    }
}
