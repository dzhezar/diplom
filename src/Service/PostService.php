<?php


namespace App\Service;


use App\Entity\Category;
use App\Entity\Post;
use App\Repository\PostRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostService
{
    private $postRepository;
    private $em;
    private $slugger;
    private $security;

    public function __construct(PostRepository $postRepository, EntityManagerInterface $em, SluggerInterface $slugger, Security $security)
    {
        $this->postRepository = $postRepository;
        $this->em = $em;
        $this->slugger = $slugger;
        $this->security = $security;
    }

    public function getRecentPosts()
    {
        return $this->postRepository->findBy(['isVisible' => true],['created' => 'DESC'],6);
    }

    public function getVisibleByCategory(Category $category)
    {
        return $this->postRepository->findBy(['isVisible' => true, 'category' => $category], ['id' => 'DESC']);
    }

    public function getById(string $id)
    {
        return $this->postRepository->findOneBy(['id' => $id]);
    }

    public function add(FormInterface $form, string $dir)
    {
        /** @var Post $post */
        $post = $form->getData();
        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();
        $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
        // this is needed to safely include the file name as part of the URL
        $safeFilename = $this->slugger->slug($originalFilename);
        $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

        try {
            $image->move(
                $dir,
                $newFilename
            );
        } catch (FileException $e) {
            return new Response($e->getMessage(), 500);
        }
        $post->setImage($newFilename)
            ->setAuthor($this->security->getUser())
            ->setCreated(new DateTime());

        $this->em->persist($post);
        $this->em->flush();

        return true;
    }

    public function edit(FormInterface $form, string $dir)
    {
        /** @var Post $post */
        $post = $form->getData();
        /** @var UploadedFile|null $image */
        $image = $form->get('image')->getData();
        if ($image) {
            $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $this->slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

            try {
                $image->move(
                    $dir,
                    $newFilename
                );
            } catch (FileException $e) {
                return new Response($e->getMessage(), 500);
            }
            $post->setImage($newFilename);
        }
        $this->em->persist($post);
        $this->em->flush();

        return true;
    }

    public function delete(string $id)
    {
        $category = $this->postRepository->findOneBy(['id' => $id]);
        $this->em->remove($category);
        $this->em->flush();
    }
}
