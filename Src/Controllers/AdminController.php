<?php

namespace App\Controllers;

use App\Controllers\Controller;
use MDCR\core\Request;

class AdminController extends Controller
{
    public function index(Request $request) {
        return $this->view('admin/home.twig');
    }

}
