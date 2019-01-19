# Roche FPHI website

Developer information for the Clubul Calatorilor website.

- [Getting Started](#getting-started)
    - [Prerequisites](#prerequisites)
    - [Installing](#installing)
- [Git](#git)
    - [Workflow](#workflow)
- [Technologies](#technologies)
- [Service integration](#service-integration)
- [Cheatsheet](#cheatsheet)

<a name="getting-started"></a>
## 1. Getting Started
![Start](https://png.icons8.com/color/96/000000/start.png)

<a name="prerequisites"></a>
### 1.1 Prerequisites

Install [Composer](https://getcomposer.org/download/).

Install [Node.js](https://nodejs.org/en/download/).

<a name="installing"></a>
### 1.2 Installing

Checkout the latest codebase.

Run ```composer install``` in ```/app/``` to build Drupal and dependencies.

Navigate to ```/app/web/themes/contrib/neato``` and run ```bower install``` (if you don't have Bower installed already run  ```npm install bower``` before ```bower install```).

Run ```npm install``` in ```/app/web/themes/custom/clubulcalatorilor_theme``` to install dependencies.

Navigate to ```/web/sites/default/``` then copy ```default.settings.php``` and rename to ```settings.php``` and configure the database connection.

An existing database backup of the Drupal site can be imported through the 'Backup and migrate' module or directly via MySQL.

Any required public files should be copied to web/sites/default/files.

<a name="git"></a>
## 2. Git
![Git](https://png.icons8.com/color/96/000000/git.png)


<a name="workflow"></a>
### 2.1 Workflow

At any given time the repo should contain the following branches:

develop - Latest tested/stable codebase
master - Mirror of the production codebase
Commit messages should be semantic and shouldn't include a reference to a task number.

In-progress tasks should be worked on in a seperate branch.

Only after a task is completed and tested should it be merged into the develop branch. Prior to merging the branch should be rebased off the latest develop branch and commits optionally squashed.

Prior to deployments to the production site the develop branch should be merged into master. In cases where only a subset of the commits in the develop branch need to be included in the master branch, these commits should instead be cherry picked (with the develop branch rebased off master following this).

<a name="technologies"></a>
## 3. Technologies
![Robot](https://png.icons8.com/color/96/000000/robot.png)

The Drupal site is scaffolded using the Drupal Composer template (documentation for this is included in drupal-composer.md in the root of the project). Module/theme/patch dependencies should all be managed using Composer.

The Configuration synchronization module is used to manage site config. Any changes made to config should be exported to the ```../config/sync``` directory by running ```drush cex```. Updated config can be imported by running ```drush cim```.

The custom theme uses npm for package management and Webpack for compiling assets. Run ```npm run build``` in the root of the custom theme to compile assets (or ```npm run dev``` to watch for file changes).

<a name="service-integration"></a>
## 4. Service integration
![Services](https://png.icons8.com/color/96/000000/services.png)

Sendgrid

Stripe

<a name="cheatsheet"></a>
## 5. Cheatsheet
![Copybook](https://png.icons8.com/color/96/000000/copybook.png)

Install contrib module:
```
composer require drupal/<modulename>
```

Import Drupal configuration:
```
drush cim
```

Export drupal configuration:
```
drush cex
```

Clear cache using drush:
```
drush cr
```

Compile assets in ```web/themes/custom/clubulcalatorilor_theme```
```
gulp
```