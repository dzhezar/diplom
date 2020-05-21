<?php


namespace App\Mapper;


use App\Entity\Category;
use App\Form\Category\CategoryForm;

class CategoryMapper
{
    public function entityToFormDto(Category $entity): CategoryForm
    {
        return new CategoryForm(
            $entity->getName(),
            $entity->getIsVisible()
        );
    }
}
