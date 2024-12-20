<?php

/**
 * @file HomeController.php
 * @author Nathan Chauveau, David Dieperink, Julien Schneider
 * @version 19.12.2024
 * @description Controller for managing the display of the home page in the application.
 *
 * @details Handles the logic for rendering the home page view.
 *          Acts as the entry point for home-related requests.
 */

require_once APP_DIR . '/core/Controller.php';

/**
 * Class HomeController
 *
 * Handles the display of the application's homepage by loading the appropriate view.
 * Extends the base `Controller` class for core functionalities.
 */
class HomeController extends Controller
{
    /**
     * Displays the home page.
     * Includes the home view file to render the page.
     *
     * @return void
     */
    public function show()
    {
        require_once VIEW_DIR . '/home/home.php';
    }
}
