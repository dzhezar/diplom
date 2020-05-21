<?php


namespace App\Controller\Admin\Category;


use App\Entity\Category;
use App\Form\CategoryForm;
use App\Mapper\CategoryMapper;
use App\Repository\CategoryRepository;
use App\Service\CategoryService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
  * Require ROLE_ADMIN for *every* controller method in this class.
  *
  * @IsGranted("ROLE_ADMIN")
 */
class IndexController extends AbstractController
{
    private $categoryRepository;

    private $paginator;

    private $categoryMapper;

    private $categoryService;

    public function __construct(CategoryRepository $categoryRepository, PaginatorInterface $paginator, CategoryMapper $categoryMapper, CategoryService $categoryService)
    {
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
        $this->categoryMapper = $categoryMapper;
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        $categories = $this->paginator->paginate($this->categoryRepository->findAll(), $request->query->getInt('page',1),10);
        return $this->render('/admin/category/index.html.twig', ['categories' => $categories]);
    }

    public function add(Request $request)
    {
        $form = $this->createForm(CategoryForm::class, new Category());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->add($form->getData());
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function edit(string $id, Request $request)
    {
        $category = $this->categoryRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(CategoryForm::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->edit($form->getData());
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function delete(string $id)
    {
        $this->categoryService->delete($id);
        return $this->redirectToRoute('admin_category_index');
    }
}
