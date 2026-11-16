<?php

namespace App\Controllers;

use App\Models\Movie;

class HomeController extends Controller {
    public function index() {
        $movies = Movie::getNowShowing();
        return $this->view('home.index', compact('movies'));
    }

    public function contact() {
        return $this->view('home.contact');
    }
}
