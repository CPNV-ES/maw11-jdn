<?php

require_once APP_DIR . '/core/Controller.php';

class HomeController extends Controller
{
    public function show()
    {
        require_once VIEW_DIR . '/home/home.php';
    }
}
