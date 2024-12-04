# MAW11-JDN

## Description

This project is designed to .... and the main features are ...

## Getting Started

### Prerequisites

List all dependencies and their version needed by the project as :

* DataBase Engine : SQLite `php8.3-sqlite3`
  + Make sure to enable the `pdo_sqlite` extension in your `php.ini` file
* IDE used :
  + [Visual Studio Code (v1.92.2)](https://code.visualstudio.com/updates/v1_92)
* Package manager :
  + [Composer v2.7.9](https://getcomposer.org/download/)
* Packages :
  + [Xdebug (v3.3.2)](https://xdebug.org/docs/install)
  + [PHPUnit (v9.6.20)](https://docs.phpunit.de/en/11.3/installation.html#installing-phpunit-with-composer)
  + [Zend Engine (4.3.11)]
* OS supported :
  + [Debian 11 (bullseye)](https://www.debian.org/releases/bullseye/debian-installer/index)
  + [macOS (Sonoma 14.5)](https://www.iclarified.com/91544/where-to-download-macos-sonoma)
  + [Windows (10.22H2)](https://www.microsoft.com/fr-fr/software-download/windows10%20)
* Languages :
  + [PHP (8.3.11)](https://www.php.net/downloads.php)
* Extensions :
  + [EditorConfig for VS Code (v0.16.4)](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig)
  + [Prettier - Code formatter (v11.0.0)](https://marketplace.visualstudio.com/items?itemName=esbenp.prettier-vscode)
  + [PHP Tools for VSCode (v1.51.15986)](https://www.devsense.com/en/features#vscode)

### Configuration

1. Install PHP
   1. [macOS](https://www.php.net/manual/en/install.macosx.packages.php)
   2. [Windows](https://www.geeksforgeeks.org/how-to-install-php-in-windows-10/)
   3. [On Debian based distros](https://php.watch/articles/php-8.3-install-upgrade-on-debian-ubuntu#php83-debian-quick)
2. [Install Composer](https://getcomposer.org/download/)
3. [Install Git](https://git-scm.com/book/en/v2/Getting-Started-Installing-Git)
4. [Install Xdebug](https://xdebug.org/docs/install)

For Git Flow, it depends on which OS you are. If you are using Windows, it's all good, it already comes installed with git. For the others, [follow this tutorial](https://skoch.github.io/Git-Workflow/).

In this project, we have been using Visual Studio Code as our main IDE.

## Deployment

### On dev environment

1. Clone the repository and install the required dependencies

```bash
git clone https://github.com/CPNV-ES/maw11-jdn.git
cd maw11-jdn
composer install
```

2. Setup `main` branch and init Git Flow for the project

```bash
git switch main

git flow init
```

3. Setup the database and the configuration file

> Create the configuration file and edit the database file name     

```bash
cp config/config.example.php config/config.php
```

> Create the sqlite file

```bash
cp db/database.example.sqlite db/database.sqlite
```

Once you've created the database, you'll need to run the SQL scripts located in the **db** directory. The `create_database.sql` script to create the database and the `insert_fake_data.sql` script to insert fake data into the database.

4. Run PHP dev server

```bash
php -S localhost:8000 -t public/
```

#### How to run the tests?

```bash
./vendor/bin/phpunit tests/*.php
```

## Directory structure

```bash
./maw11-jdn
├── app
│   ├── controllers
│   │   ├── ExerciseController.php
│   │   └── HomeController.php
│   ├── core
│   │   ├── Controller.php
│   │   ├── Database.php
│   │   ├── Model.php
│   │   └── Router.php
│   ├── models
│   │   ├── AnswerModel.php
│   │   ├── ExerciseModel.php
│   │   ├── FieldModel.php
│   │   └── FulfillmentModel.php
│   └── views
│       ├── home
│       └── layouts
├── composer.json
├── composer.lock
├── config
│   ├── config.example.php
│   └── config.php
├── db
│   ├── create_database.sql
│   ├── database.example.sqlite
│   ├── database.sqlite
│   └── insert_fake_data.sql
├── docs
│   ├── looper-features.md
│   ├── looper-specs.md
│   └── repo-structuring.md
├── logs
├── public
│   ├── css
│   │   ├── create-exercise.css
│   │   ├── create-field.css
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
└── tests
    ├── DatabaseTest.php
    └── db
        ├── create_database_test.sql
        ├── database_test.sqlite
        └── insert_fake_data_test.sql
```

## Collaborate

* Take time to read some readme and find the way you would like to help other developers collaborate with you.

* They need to know:
  + [How to propose a new feature (issue, pull request)](https://github.com/CPNV-ES/maw11-jdn/issues/new/choose)
  + [How to write code](https://www.php-fig.org/psr/psr-12/)
  + [How to commit](https://www.conventionalcommits.org/en/v1.0.0/)
  + [How to use your workflow - GitFlow](https://nvie.com/posts/a-successful-git-branching-model/)

## License

* [Choose the license adapted to your project](https://docs.github.com/en/repositories/managing-your-repositorys-settings-and-features/customizing-your-repository/licensing-a-repository).

## Contact

* David : <david.dieperink@eduvaud.ch>, [GitHub](https://github.com/dieperid)
* Julien : <julien.schneider@eduvaud.ch>, [GitHub](https://github.com/T5uy0)
* Nathan : <nathan.chauveau@eduvaud.ch>, [GitHub](https://github.com/NathanChauveau)
