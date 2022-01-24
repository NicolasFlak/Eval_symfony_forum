<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Form\SubCategoryFormType;
use App\Repository\SubCategoryRepository;
use App\Repository\ThreadRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SubCategoryController extends AbstractController
{
    private SubCategoryRepository $subCategoryRepository;
    private EntityManagerInterface $entityManager;

    /**
     * @param SubCategoryRepository $subCategoryRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(SubCategoryRepository $subCategoryRepository, EntityManagerInterface $entityManager)
    {
        $this->subCategoryRepository = $subCategoryRepository;
        $this->entityManager = $entityManager;
    }


    #[Route('/sub-category/{id}', name: 'sub_category_index')]
    public function index(string $id, Request $request): Response
    {
        $subCategoryEntity = $this->subCategoryRepository->find($id);

        return $this->render('sub_category/index.html.twig', [
            'subCategory' => $subCategoryEntity,
        ]);
    }

    #[Route('/sub-category/add', name: 'sub_category_add')]
    public function addSubCategory(Request $request): Response
    {
        $subCategory = new SubCategory();
        $form = $this->createForm(SubCategoryFormType::class, $subCategory);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->entityManager->persist($subCategory);
            $this->entityManager->flush();

            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub-category/add.html.twig', [
            'subCategoryForm' => $form->createView(),
        ]);
    }


    #[Route('/sub-category/edit/{id}', name: 'sub_category_edit')]
    public function editSubCategory(string $id, Request $request): Response
    {
        $subCatEntity = $this->subCategoryRepository->find($id);
        $form = $this->createForm(SubCategoryFormType::class, $subCatEntity);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($subCatEntity);
            $this->entityManager->flush();
            return $this->redirectToRoute('sub_category_index');
        }

        return $this->render('sub-category/edit.html.twig', [
            'subCatForm' => $form->createView(),
        ]);
    }

}
