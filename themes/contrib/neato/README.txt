    _   __           __
   / | / /__  ____ _/ /_____
  /  |/ / _ \/ __ `/ __/ __ \
 / /|  /  __/ /_/ / /_/ /_/ /
/_/ |_/\___/\__,_/\__/\____/

INSTALLATION
------------

npm and Gulp are required to manage assets.

First, you will need to install NodeJS.

Then install Gulp globally with 'npm install -g gulp' from the command line. On
some setups, sudo may be required.

Create a subtheme. See the BUILD A THEME WITH DRUSH section below on how to do
that. Continue here when finished.

From your subtheme directory, enter 'npm install' in the command line. This will
install the required tools and libraries to compile the assets.

Enable and set your subtheme as default. Then visit the front page to see the
sample page.

Make a copy of example.config.js and set your local development settings here.
This file is going to be ignored by Git to prevent breaking of team-members' dev
setup.

cp example.config.js config.js

Run 'gulp' from the subtheme command line to compile CSS from SASS. Gulpfile.js
controls what happens in this process. Feel free to add your own tools into this
file to facilitate development. Saving SCSS/JS/Twig files will trigger tasks
like CSS generation, JS generation, source map generation, and if configured,
cache rebuilding.

BUILD A THEME WITH DRUSH
------------------------

It is highly encouraged to use Drush to generate a sub theme for editing. Do not
edit the parent 'neato' theme!

Note: As of https://www.drupal.org/project/drupal/issues/2002606 themes can't
      provide Drush 9 commands for now. So this section actually requires an
      older Drupal < 8.4 with Drush 8. Which is pretty bad. We'll provide
      Drush 9 commands as soon as the mentioned issue gets fixed.

Trick: Have a sandbox Drupal < 8.4 which with Drush 8 available. Build the
       subtheme with drush in that sandbox and then copy the new subtheme over
       to your 8.4+ Drupal's themes/custom folder.

  1. Enable the Neato theme.
  2. Enter the drush command: drush ngt [THEMENAME] [Description !Optional]

BUILD A THEME MANUALLY (NOT RECOMMENDED)
----------------------------------------

1. Copy the whole STARTER folder into themes/custom so it will be
   themes/custom/STARTER.
2. Now rename this STARTER folder to your [THEMENAME].
3. Rename STARTER.info.yml.txt to [THEMENAME].info.yml and edit the file to
   change all STARTER references to [THEMENAME].
4. Rename STARTER.theme to [THEMENAME].theme and edit the file to change all
   STARTER references to [THEMENAME].
5. Rename STARTER.libraries.yml to [THEMENAME].libraries.yml and edit the file
   to change all STARTER references to [THEMENAME].
6. Rename scss/STARTER.scss to scss/[THEMENAME].scss.
7. Rename css/STARTER.css to css/[THEMENAME].css.
8. Rename templates/base/page.html.twig.txt to templates/base/page.html.twig.
