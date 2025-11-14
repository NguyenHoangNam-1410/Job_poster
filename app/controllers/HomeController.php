<?php

class HomeController {
    public function index(){
        require_once '../app/views/public/home.php';
        exit;
    }
    public function adminIndex(){
        require_once '../app/views/admin/home.php';
        exit;
    }

    public function staffIndex(){
        require_once '../app/views/staff/home.php';
        exit;
    }
    public function employerIndex(){
        require_once '../app/views/employer/home.php';
        exit;
    }
}