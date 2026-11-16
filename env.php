<?php

const BASE_URL = 'http://localhost/ASSGD1/'; 
const BASE_IMAGE = BASE_URL . "images/";

const ROOT_DIR = __DIR__;
const ROOT_IMAGE = ROOT_DIR . "/images/";

 // Database 
 const HOST = 'localhost';
 const DBNAME = 'ass_php2';
 const USERNAME = 'root';
 const PASSWORD = '';
 const PORT = '3306';

if (!function_exists('poster_url')) {
    function poster_url($poster) {
        if (empty($poster)) {
            return '';
        }
        $poster = trim($poster);
        if (strpos($poster, 'http://') === 0 || strpos($poster, 'https://') === 0 || strpos($poster, 'data:image/') === 0) {
            return $poster;
        }
        if (strpos($poster, '/ASSGD1/') === 0) {
            $poster = substr($poster, 8);
        }
        $poster = ltrim($poster, '/\\');
        return BASE_URL . $poster;
    }
}