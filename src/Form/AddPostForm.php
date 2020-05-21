<?php


namespace App\Form;


use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddPostForm extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('category', ChoiceType::class, [
                'choices'  => $this->getCategories(),
                'choice_label' => function ($category) {
                    /* @var Category $category */
                    return $category->getName();
                },
            ])
            ->add(
                'title',
                TextType::class
            )
            ->add(
                'text',
                TextareaType::class
            )
            ->add(
                'image',
                FileType::class
            )
            ->add(
                'text',
                TextareaType::class
            )
            ->add(
                'is_visible',
                CheckboxType::class
            )
        ;
    }

    private function getCategories()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }
}
