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

class EditPostForm extends AbstractType
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
                TextType::class,
                ['required' => false]
            )
            ->add(
                'text',
                TextareaType::class,
                ['required' => false]
            )
            ->add(
                'image',
                FileType::class,
                ['required' => false,
                 'mapped' => false]
            )
            ->add(
                'text',
                TextareaType::class,
                ['required' => false]
            )
            ->add(
                'is_visible',
                CheckboxType::class,
                ['required' => false]
            )
        ;
    }

    private function getCategories()
    {
        return $this->em->getRepository(Category::class)->findAll();
    }
}
