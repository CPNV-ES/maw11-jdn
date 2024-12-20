# Repository structuration

```bash
./maw11-jdn
├── app
│   ├── controllers                     # Controllers folder
│   │   ├── MainController.php
│   │   └── HomeController.php
│   ├── core                            # Core folder (default model, controler, ...)
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   └── Model.php
│   ├── models                          # Models folder
│   │   ├── AnswerModel.php
│   │   ├── ExerciseModel.php
│   │   ├── FieldModel.php
│   │   └── FulfillmentModel.php
│   └── views                           # Views folder
│       ├── home                        # Main pages of project
│       │   ├── all-fulfill.php
│       │   ├── create-exercise.php
│       │   ├── create-field.php
│       │   ├── edit-field-page.php
│       │   ├── field-exercise.php
│       │   ├── fulfill-exercise.php
│       │   ├── home.php
│       │   ├── manage-exercise.php
│       │   ├── response-exercise.php
│       │   ├── result-exercise.php
│       │   ├── result-field.php
│       │   └── take-exercise.php
│       └── layouts                     # Layouts of the project
│           ├── app-layout.php
│           └── header.php
├── composer.json
├── composer.lock
├── config                              # Configuration folder
│   ├── config.example.php
│   └── config.php
├── database                            # Database folder
│   └── maw11_jdn.sql
├── docs                                # Documentation of the project
│   ├── looper-features.md
│   ├── looper-specs.md
│   └── repo-structuring.md
├── LICENSE
├── public                              # Public file of the project
│   ├── css                             # CSS folder of the project
│   │   ├── all-fulfill.css
│   │   ├── create-exercise.css
│   │   ├── create-field.css
│   │   ├── edit-field-page.css
│   │   ├── fulfill-exercise.css
│   │   ├── home-page.css
│   │   ├── manage-exercise.css
│   │   ├── result-pages.css
│   │   ├── style.css
│   │   └── take-exercise.css
│   ├── images
│   │   └── logo.png
│   └── index.php
├── README.md
├── run
└── tests                               # Tests of the project
    ├── DatabaseTest.php
    └── database
        └── maw11_jdn_test.sql
```

## Folder and file creation

### Folder creation

```bash
mkdir app app/controllers app/models app/views app/views/layouts app/views/home app/core public public/css public/images logs docs config
```

### File creation

For folders that don't have a file at the start, we generate the `.gitkeep` file to keep track of them.

```bash
touch config/config.php app/controllers/.gitkeep app/models/.gitkeep app/views/home/.gitkeep app/views/layouts/.gitkeep app/core/Database.php app/core/Router.php app/core/Controller.php app/core/Model.php public/css/style.css public/images/.gitkeep public/index.php logs/.gitkeep
```

## Explanation of structure

### Folder description

1. **/app** : Contains the heart of the application.

    - **/controllers** : Controllers manage application logic.
    - **/models** : Models represent business logic and interact with the database.
    - **/views** : Views are responsible for displaying data to users. Sub-folders can correspond to different pages or sections of the site.
    - **/core** : Contains the basic classes and utilities of the MVC framework (such as the router, parent classes for controllers and models and also database).

2. **/public** : Files accessible directly by users via the web (such as CSS, images).

    - **index.php** : The application's main entry point. This file includes framework initialization and routing.

3. **/docs** : Contains all the documentation of the project.

4. **/config** : Configuration files, such as database connection parameters.

### Example of organization

* **HomeController.php** (in `/controllers`) could have an **index()** method that retrieves data via a **User** model (in `/models`) and passes it to an **index.php** view (in `/views/home`).

### Links to understand MVC

* [StudentsTutorial.com - MVC Introduction with PHP](https://www.studentstutorial.com/php/mvc/mvc-structure#)
* [Sitepoint.com - MVC Pattern with PHP](https://www.sitepoint.com/the-mvc-pattern-and-php-1/)
