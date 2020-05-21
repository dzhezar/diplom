<?php


namespace App\Controller\Admin\Post;


use App\Entity\Post;
use App\Form\AddPostForm;
use App\Form\EditPostForm;
use App\Repository\PostRepository;
use App\Service\PostService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Security;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_WRITER")
 */
class IndexController extends AbstractController
{
    private $postRepository;
    private $postService;
    private $paginator;
    private $security;

    public function __construct(PostRepository $postRepository,  PostService $postService, PaginatorInterface $paginator, Security $security)
    {
        $this->postRepository = $postRepository;
        $this->postService = $postService;
        $this->paginator = $paginator;
        $this->security = $security;
    }
    public function index(Request $request)
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $posts = $this->paginator->paginate($this->postRepository->findBy([], ['created' => 'DESC']), $request->query->getInt('page',1),10);
        } else {
            $posts = $this->paginator->paginate($this->postRepository->findBy(['author' => $this->security->getUser()], ['created' => 'DESC']), $request->query->getInt('page',1),10);
        }
        return $this->render('admin/post/index.html.twig', ['posts' => $posts]);
    }

    public function add(Request $request)
    {
        $form = $this->createForm(AddPostForm::class, new Post());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->add($form, $this->getParameter('images_directory'));
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(string $id, Request $request)
    {
        $category = $this->postRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(EditPostForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->postService->edit($form, $this->getParameter('images_directory'));
            return $this->redirectToRoute('admin_post_index');
        }

        return $this->render('admin/post/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(string $id)
    {
        $this->postService->delete($id);
        return $this->redirectToRoute('admin_post_index');
    }
}
