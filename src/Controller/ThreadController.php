<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Thread;
use App\Form\PostFormType;
use App\Form\ThreadFormType;
use App\Repository\PostRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    private ThreadRepository $threadRepository;
    private SubCategoryRepository $subCategoryRepository;
    private PostRepository $postRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param ThreadRepository $threadRepository
     * @param SubCategoryRepository $subCategoryRepository
     * @param PostRepository $postRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(ThreadRepository $threadRepository, SubCategoryRepository $subCategoryRepository, PostRepository $postRepository, EntityManagerInterface $entityManager)
    {
        $this->threadRepository = $threadRepository;
        $this->subCategoryRepository = $subCategoryRepository;
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
    }


    #[Route('/thread/{id}', name: 'thread_index')]
    public function index(string $id, Request $request): Response
    {

        $threadEntity = $this->threadRepository->find($id);
//        $user = $this->getUser();
//        $pu = $threadEntity->getId();
//        $threadAuthor = $this->postRepository->findAuthor($pu);
        return $this->render('thread/index.html.twig', [
            'thread' => $threadEntity,
//            'threadAuthor'=>$threadAuthor
        ]);
    }

    #[Route('/subCategory/{id}/thread/add', name: 'thread_add')]
    public function addThread(Request $request, string $id): Response
    {
        $subCategory = $this->subCategoryRepository->find($id);

        $thread = new Thread();
        $form = $this->createForm(ThreadFormType::class, $thread);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $thread->setSubCategory($subCategory);
            $thread->setCreatedAt(new \DateTime());
            $thread->setUser($this->getUser());
            $this->entityManager->persist($thread);
            $this->entityManager->flush();

            return $this->redirectToRoute('sub_category_index', ['id' => $subCategory->getId()]);
        }

        return $this->render('thread/add.html.twig', [
            'threadForm' => $form->createView(),
        ]);
    }


    #[Route('/subCategory/{id}/thread/edit', name: 'thread_edit')]
    public function editThread(string $id, Request $request): Response
    {
        $threadEntity = $this->threadRepository->find($id);
        $form = $this->createForm(ThreadFormType::class, $threadEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($threadEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('thread_index', ['id' => $threadEntity->getId()]);
        }

        return $this->render('thread/edit.html.twig', [
            'threadForm' => $form->createView(),
        ]);
    }

    #[Route('/thread/{id}/post/add', name: 'post_add')]
    public function addPost(Request $request, string $id): Response
    {
        $thread = $this->threadRepository->find($id);

        $post = new Post();
        $form = $this->createForm(PostFormType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $post->setThread($thread);
            $post->setCreatedAt(new \DateTime());
            $post->setUser($this->getUser($id));
            $this->entityManager->persist($post);
            $this->entityManager->flush();

            return $this->redirectToRoute('thread_index', ['id' => $thread->getId()]);
        }

        return $this->render('post/add.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }

    #[Route('/thread/{id}/post/edit/{idPost}', name: 'post_edit')]
    public function editPost(string $id, string $idPost, Request $request): Response
    {
//        $threadId = $this->threadRepository->find($id);
//        $postId = $this->postRepository->find($idPost);
//        dump($postId);

        $postEntity = $this->postRepository->find($idPost);
        $form = $this->createForm(PostFormType::class, $postEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($postEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('thread_index', ['id' => $id]);
        }

        return $this->render('post/edit.html.twig', [
            'postForm' => $form->createView(),
        ]);
    }

}
