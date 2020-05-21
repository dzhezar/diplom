<?php


namespace App\Service;


use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\AsciiSlugger;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryService
{
    private $categoryRepository;
    private $slugger;
    private $em;

    public function __construct(CategoryRepository $categoryRepository, SluggerInterface $slugger, EntityManagerInterface $em)
    {
        $this->categoryRepository = $categoryRepository;
        $this->slugger = $slugger;
        $this->em = $em;
    }

    public function getBySlug(string $slug)
    {
        return $this->categoryRepository->findOneBy(['slug' => $slug]);
    }

    public function add(Category $category)
    {
        $category->setSlug($this->slugger->slug($category->getName()));
        $this->em->persist($category);
        $this->em->flush();
    }

    public function edit(Category $category)
    {
        $category->setSlug($this->slugger->slug($category->getName()));
        $this->em->persist($category);
        $this->em->flush();
    }

    public function delete(string $id)
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $this->em->remove($category);
        $this->em->flush();
    }
}
