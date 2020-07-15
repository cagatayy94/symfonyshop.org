<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Route("/admin", name="admin_")
 */
class DefaultController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function loginAction(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $tokenProvider = $this->container->get('security.csrf.token_manager');
        $token = $tokenProvider->getToken('authenticate')->getValue();

        return $this->render('Admin/Default/login.html.php', ['last_username' => $lastUsername, 'error' => $error, 'token' => $token]);
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This method can be blank - it will be intercepted by the logout key on your firewall');
    }


    /**
     * @Route("/", name="dashboard")
     */
    public function dashboardAction()
    {
        $admin = $this->getUser();
        
        if (!$admin->hasRole('site_dashboard_show')) {
            throw new \Exception('Yetkisiz erişim');
        }

        return $this->render('Admin/Default/dashboard.html.php', ['admin' => $admin]);
    }
}
