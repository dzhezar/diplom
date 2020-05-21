<?php


namespace App\Controller\Admin;


use App\Form\EditUserForm;
use App\Mapper\UserMapper;
use App\Repository\UserRepository;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    private $userRepository;
    private $userMapper;
    private $userService;
    private $paginator;

    public function __construct(UserRepository $userRepository, UserMapper $userMapper, UserService $userService, PaginatorInterface $paginator)
    {
        $this->userRepository = $userRepository;
        $this->userMapper = $userMapper;
        $this->userService = $userService;
        $this->paginator = $paginator;
    }
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $users = $this->paginator->paginate($this->userRepository->findAll(), $request->query->getInt('page',1),10);

        return $this->render('admin/user/index.html.twig', ['users' => $users]);
    }

    public function edit(string $id, Request $request)
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        $userDto = $this->userMapper->entityToFormDto($user);
        $form = $this->createForm(EditUserForm::class, $userDto);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->edit($this->userMapper->formDtoToEntity($form->getData(), $user));
            return $this->redirectToRoute('admin_user_index');
        }
        return $this->render('admin/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
