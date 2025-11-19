<?php

class HomeController {
    public function index(){
        require_once '../app/views/public/home.php';
        exit;
    }
}