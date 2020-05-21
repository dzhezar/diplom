<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CategoryController extends AbstractController
{
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    public function getVisibleCategories()
    {
        $categories = $this->categoryRepository->findBy(['isVisible' => true]);
        return $this->render('category/header_categories.html.twig', ['categories' => $categories]);
    }
}
