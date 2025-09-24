<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        // Redirect straight to login page
        return $this->redirectToRoute('app_login');
    }

    #[Route('/login', name: 'app_login')]
    public function login(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            dd(['username' => $username, 'password' => $password]);
        }

        return $this->render('login/index.html.twig');
    }

    #[Route('/signup', name: 'app_signup')]
    public function signup(Request $request): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $username = $request->request->get('username');
            $password = $request->request->get('password');

            dd(['email' => $email, 'username' => $username, 'password' => $password]);
        }

        return $this->render('signup/signup.html.twig');
    }
}
