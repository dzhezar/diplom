<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Form\AddCommentForm;
use App\Service\CategoryService;
use App\Service\CommentService;
use App\Service\PostService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class IndexController extends AbstractController
{
    private $postService;
    private $categoryService;
    private $paginator;
    private $commentService;

    public function __construct(PostService $postService, CategoryService $categoryService, PaginatorInterface $paginator, CommentService $commentService)
    {
        $this->postService = $postService;
        $this->categoryService = $categoryService;
        $this->paginator = $paginator;
        $this->commentService = $commentService;
    }

    public function index()
    {
        $recentPosts = $this->postService->getRecentPosts();
        return $this->render('index.html.twig',['recentPosts' => $recentPosts]);
    }

    public function showCategory(string $slug, Request $request)
    {
        $category = $this->categoryService->getBySlug($slug);
        $categoryPosts = $this->paginator->paginate($this->postService->getVisibleByCategory($category), $request->query->getInt('page',1),6);
        return $this->render('category/index.html.twig', ['category' => $category, 'categoryPosts' => $categoryPosts]);
    }

    public function showPost(string $id, Request $request)
    {
        $post = $this->postService->getById($id);
        $comments = $this->commentService->getCommentsForPost($post);
        $form = $this->createForm(AddCommentForm::class, (new Comment())->setPost($post));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->add($form->getData());
            $this->redirectToRoute('post', ['id' => $id]);
        }

        return $this->render('single_post.html.twig', [
            'post' => $post, 'comments' => $comments, 'form' => $form->createView()]);
    }

    public function deleteComment(string $id, Request $request)
    {
        $post = $this->commentService->delete($id);
        return $this->redirectToRoute('post', ['id' => $post]);
    }
}
