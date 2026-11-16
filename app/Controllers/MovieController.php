<?php

namespace App\Controllers;

use App\Models\Movie;
use App\Models\Category;

class MovieController extends Controller {
    public function index() {
        $categories = Category::all();
        $movies = Movie::getAllCategory();
        return $this->view('movie.index', compact('movies', 'categories'));
    }

    public function detail($id = null) {
        $movie = Movie::find($id);
        return $this->view('movie.detail', compact('movie'));
    }
}
