<?php


namespace App\Form;


use App\Entity\Category;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditUserForm extends AbstractType
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', ChoiceType::class, [
                'choices'  => [
                    'ROLE_ADMIN',
                    'ROLE_WRITER',
                    'ROLE_USER'
                ],
                'choice_label' => function ($role) {
                    return $role;
                },
            ])
            ->add(
                'name',
                TextType::class,
                ['required' => false]
            )
        ;
    }
}
