<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\SubCategory;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private CategoryRepository $categoryRepository;
    private SubCategoryRepository $subCategoryRepository;

    /**
     * @param CategoryRepository $categoryRepository
     * @param SubCategoryRepository $subCategoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository, SubCategoryRepository $subCategoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $category = $this->categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'categories' => $category,
        ]);
    }


}
