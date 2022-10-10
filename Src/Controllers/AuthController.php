<?php

namespace App\Controllers;

use MDCR\core\Request;
use MDCR\Models\User;

class AuthController extends Controller
{
    // public function index($id, $name)
    public function login()
    {
        if (self::auth()) {
            header('Location: /home');
        }
        $this->view('auth/login.twig');
    }

    public function authorize(Request $request): void
    {
        $data = $request->all();
        $user = new User(); // \R::findOne('user', 'email = ?', [$data['email']]);

        $user = $user->findOneByParams(['email' => $data['email']]);
        $authenticad = password_verify($data['password'], $user->password);
        if ($authenticad) {
            $_SESSION['logged'] = 'true';
            $_SESSION['user_id'] = $user->id;
            $next = $_SESSION['nextUrl'] ? $_SESSION['nextUrl'] : '/home';
            unset($_SESSION['nextUrl']);
            header('Location: ' . $next);
        }
    }

    public function create(Request $request)
    {
        $user = new User();
        $data = $request->all();
        if (isset($data['password'])) {
            $options = [
                'cost' => 10
            ];
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, $options);
        }
        if ($user->create($data)) {
            return $this->redirect('/home');
        }
    }

    public function register()
    {
        return $this->view('auth/register.twig');
    }

    public function logout()
    {
        $_SESSION['logged'] = 'false';
        unset($_SESSION['user_id']);
        header('Location: /');
    }

    public static function auth()
    {
        if (isset($_SESSION['logged']) && $_SESSION['logged'] == 'true') {
            return true;
        } else {
            // header('Location: /login');
            return false;
        }
    }
}
