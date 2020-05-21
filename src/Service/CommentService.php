<?php


namespace App\Service;


use App\Entity\Comment;
use App\Entity\Post;
use App\Repository\CommentRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class CommentService
{
    private $commentRepository;
    private $em;
    private $security;

    /**
     * CommentService constructor.
     * @param CommentRepository $commentRepository
     * @param EntityManagerInterface $em
     * @param Security $security
     */
    public function __construct(CommentRepository $commentRepository, EntityManagerInterface $em, Security $security)
    {
        $this->commentRepository = $commentRepository;
        $this->em = $em;
        $this->security = $security;
    }

    public function getCommentsForPost(Post $post)
    {
        return $this->commentRepository->findBy(['post' => $post]);
    }

    public function add(Comment $comment)
    {
        $comment->setAuthor($this->security->getUser())
            ->setCreated(new DateTime());
        $this->em->persist($comment);
        $this->em->flush();
    }

    public function delete(string $id)
    {
        $comment = $this->commentRepository->findOneBy(['id' => $id]);
        $this->em->remove($comment);
        $this->em->flush();
        return $comment->getPost()->getId();
    }
}
