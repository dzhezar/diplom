<?php


namespace App\Controller\Security;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler extends AbstractController implements AccessDeniedHandlerInterface
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    public function handle(Request $request, AccessDeniedException $accessDeniedException)
    {
        return $this->redirectToRoute($this->isGranted('ROLE_WRITER') ? 'admin' : 'index');
    }
}
