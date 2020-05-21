<?php


namespace App\Controller\Admin;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * Require ROLE_ADMIN for *every* controller method in this class.
 *
 * @IsGranted("ROLE_WRITER")
 */
class IndexController extends AbstractController
{
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_WRITER');

        return $this->redirectToRoute('admin_post_index');
    }
}
