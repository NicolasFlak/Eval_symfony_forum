<?php

namespace App\Controller;

use App\Entity\Thread;
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

        return $this->render('thread/index.html.twig', [
            'thread' => $threadEntity,
        ]);
    }

    #[Route('/thread/subCategory/{id}/add', name: 'thread_add')]
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


    #[Route('/thread/edit/{id}', name: 'thread_edit')]
    public function editThread(string $id, Request $request): Response
    {
        $threadEntity = $this->threadRepository->find($id);
        $form = $this->createForm(ThreadFormType::class, $threadEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($threadEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('thread_index');
        }

        return $this->render('thread/edit.html.twig', [
            'threadForm' => $form->createView(),
        ]);
    }
}
