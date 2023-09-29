<?php

namespace App\Controllers;
/* Handle CORS */

// // Specify domains from which requests are allowed
// header('Access-Control-Allow-Origin: [::1]:8080');

// // Specify which request methods are allowed
// header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');

// // Additional headers which may be sent along with the CORS request
// header('Access-Control-Allow-Headers: X-Requested-With,Authorization,Content-Type');

// // Set the age to 1 day to improve speed/caching.
// header('Access-Control-Max-Age: 86400');

// // Exit early so the page isn't fully loaded for options requests
// if (strtolower($_SERVER['REQUEST_METHOD']) == 'options') {
//     exit();
// }


use MDCR\core\Request;
use MDCR\Models\User;
use Google\Client;
use Google_Client;

class AuthController extends Controller
{
    // public function index($id, $name)
    public function login()
    {
        if (self::auth()) {
            header('Location: /cursos');
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
            $next = $_SESSION['nextUrl'] ? $_SESSION['nextUrl'] : '/cursos';
            unset($_SESSION['nextUrl']);
            header('Location: ' . $next);
        }
    }

    public function authorizeGoogle(Request $request)
    {        
        $data = $request->all();
        $userModel = new User(); // \R::findOne('user', 'email = ?', [$data['email']]);

        $client = new Google_Client(['client_id' => $data['client_id']]);
        $profile = $client->verifyIdToken($data['credential']);  

        $user = $userModel->findOneByParams(['email' => $profile['email']]);
        var_dump($profile, $user);
        if($user == null) {
            $userModel->create([
                'email' => $profile['email'],
                'name' => $profile['name']
            ]);
        }
        
        
            $_SESSION['logged'] = 'true';
            $_SESSION['user_id'] = $user->id;
            $next = $_SESSION['nextUrl'] ? $_SESSION['nextUrl'] : '/cursos';
            unset($_SESSION['nextUrl']);
            // header('Location: ' . $next);

            echo ['user' => $profile];
        
    }

    public function create(Request $request)
    {
        $user = new User();
        $data = $request->all();
        $data['type'] = 'student';
        if (isset($data['password'])) {
            $options = [
                'cost' => 10
            ];
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT, $options);
        }
        if ($user->create($data)) {
            return $this->redirect('/cursos');
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
