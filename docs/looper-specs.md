# MAW11 - Exercise Looper

## Modalités générales

### Forme d'enseignement

Coaching technique et méthodologique durant le projet

### Objectifs évaluables

L'étudiant-e est évalué-e:

-   Sur ses capacités à analyser techniquement un produit existant.
-   Sur sa manière de concevoir et réaliser une architecture logicielle modulaire.
-   Sur la qualité (forme et fond) du code réalisé.
-   Sur sa communication dans la transmission du projet à un successeur.

### Contenu du module

Il s'agit de concevoir et réaliser une application web en PHP.
Les fonctionnalités sont fournies aux élèves par un des deux moyens suivant:

-   Par un cahier des charges listant les fonctionnalités à implémenter.
-   Par la mise à disposition de l'application (sans le code source).
    Dans ce cas il s'agit donc qu'ils fassent une _copie_ de cette application.

L'utilisation de framework n'est pas autorisée. Par contre l'utilisation de librairies
fournissant un service de bas niveau est autorisé.
Exemples: moteur de templating, router simple.
Contre-exemples: ORM, composants de framework comme Symfony.

## Modalités particulières

### Echéances

Rendu intérmédiaire le 31.10.2022

Rendu final le 21.12.2022

### Rendu

Le code ainsi que la documentation **doivent** être livrés au moyen d'un repo Git de l'organisation CPNV-ES.

Le code est évalué selon les critères suivants:

-   forme: respect d'une convention d'écriture et cohérence dans l'ensemble du code
-   forme: cohérence entre les noms des fichiers et le contenu
-   fond: découpage modulaire des fonctionnalités
-   fond: respect des responsabilités dans les modules (business-logic, présentation, services, ...)
-   volume (pas les SLoC mais une estimation de la production par rapport au temps à disposition)
-   effort particulier démontré
-   La contribution au code de chaque participant donne une note individuelle qui sera la note de technique.

La documentation:

-   Contient **toutes** les informations utiles et **rien que** les informations utiles à un développeur qui reprend le flambeau. Parmi les informations utiles, on compte au minimum:
    -   Un modèle conceptuel de données, qui permet d'appréhender les aspects métiers
    -   Un modèle logique de données, qui permet d'appréhender les aspects techniques
    -   Une procédure de mise en place de l’environement de dévelopement
-   Est éditée avec n'importe quelle application, mais elle est remise en PDF.
-   Fait l'objet d'une évaluation pour le groupe dans son ensemble

### Sujet

Créer une copie de l'application "exercise looper" disponible à: https://maw-looper.mycpnv.ch

La liste des fonctionnalités se trouve [ici](ExerciseLooper-Features.md)

### Contraintes

Groupe de deux personnes hétérogène dans le niveau technique et la provenance de la formation initiale.

-   HTML CSS PHP
-   Pas de JS

L'utilisation de framework n'est pas autorisée. Par contre l'utilisation de librairies
fournissant un service de bas niveau est autorisé.
Exemples: moteur de templating, router simple.
Contre-exemples: ORM, composants de framework comme Symfony.
