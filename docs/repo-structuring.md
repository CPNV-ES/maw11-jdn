# Repository structuration

```bash
/maw11-jdn
├──	/app
│ 	├── /config
│ 	│ 	└── config.php				# Configuration file (databse, etc.)
│ 	├── /controllers
│ 	│ 	└── HomeController.php		# Controller example
│ 	├── /models
│ 	│ 	└── User.php 				# Model example
│ 	├── /views
│ 	│ 	├── /layouts
│ 	│ 	│ 	└── main.php 			# Main layout
│ 	│ 	├── /home
│ 	│ 	│ 	└── index.php 			# Main view, associated with HomeController
│ 	└── /core
│ 		├── Controller.php 			# Basic class for controllers
│ 		├── Model.php 				# Basic class for models
│		├── Database.php 			# Basic class for the database
│ 		└── Router.php 				# Route management file
├── /public
│ 	├── /css
│ 	│ 	└── style.css 				# CSS file
│ 	├── /images
│ 	│ 	└── logo.png 				# Site images
│ 	└── index.php					# Site entry point (public access)
├── /docs							# Documentation folder
└── /logs							# Log folder
	└── error.log					# Error log file
```

## Folder and file creation

### Folder creation

```bash
mkdir app app/config app/controllers app/models app/views app/views/layouts app/views/home app/core public public/css public/images logs docs
```

### File creation

For folders that don't have a file at the start, we generate the `.gitkeep` file to keep track of them.

```bash
touch app/config/config.php app/controllers/.gitkeep app/models/.gitkeep app/views/home/.gitkeep app/views/layouts/.gitkeep app/core/Database.php app/core/Router.php app/core/Controller.php app/core/Model.php public/css/style.css public/images/.gitkeep public/index.php logs/.gitkeep
```

## Explanation of structure

### Folder description :

1.  **/app** : Contains the heart of the application.

    -   **/config** : Configuration files, such as database connection parameters.
    -   **/controllers** : Controllers manage application logic.
    -   **/models** : Models represent business logic and interact with the database.
    -   **/views** : Views are responsible for displaying data to users. Sub-folders can correspond to different pages or sections of the site.
    -   **/core** : Contains the basic classes and utilities of the MVC framework (such as the router, parent classes for controllers and models and also database).

2.  **/public** : Files accessible directly by users via the web (such as CSS, images).

    -   **index.php** : The application's main entry point. This file includes framework initialization and routing.

3.  **/docs** : Contains all the documentation of the project.

4.  **/logs** : Contains all the log files for recording errors and debug information.

### Example of organization :

-   **HomeController.php** (in `/controllers`) could have an **index()** method that retrieves data via a **User** model (in `/models`) and passes it to an **index.php** view (in `/views/home`).

### Links to understand MVC

-   [StudentsTutorial.com - MVC Introduction with PHP](https://www.studentstutorial.com/php/mvc/mvc-structure#)
-   [Sitepoint.com - MVC Pattern with PHP](https://www.sitepoint.com/the-mvc-pattern-and-php-1/)
