<?php

/**
 * @file
 * Contains functions only needed for drush integration.
 */

/**
 * Implementation of hook_drush_command().
 */
function neato_drush_command() {
  $items = [];

  $items['neato-generate'] = [
    'description' => 'Create a Neato sub-theme',
    'aliases'     => ['ngt'],
    'arguments'   => [
      'name' => 'Your sub-theme name.',
    ],
    'options'     => [
      'machine_name' => 'A machine-readable name for your theme, optional. Machine name must be lowercase only, with underscores instead of spaces.',
      'description'  => 'Your sub-theme description.',
    ],
    'examples'    => [
      'drush ngt "custom theme name"' => 'Create a sub-theme with the default options.',
      'drush ngt "foo bar" "foo_bar" --description="My custom Neato theme."' => 'Create a sub-theme with additional options.',
    ],
  ];

  return $items;
}

/**
 * Create a Neato sub-theme.
 */
function drush_neato_generate($name) {
  $description = drush_get_option('description');
  $machine_name = drush_get_option('machine_name');

  if (empty($name)) {
    drush_set_error(dt("Please provide a name for the sub-theme.\nUSAGE:\tdrush ngt [name] --machine_name=[machine_name !OPTIONAL] --description=[description !OPTIONAL]\n"));
    return;
  }

  // Filter everything but letters, numbers, underscores, and hyphens.
  $machine_name = !empty($machine_name) ? preg_replace('/[^a-z0-9_-]+/', '', strtolower($machine_name)) : preg_replace('/[^a-z0-9_-]+/', '', strtolower($name));
  // Eliminate hyphens.
  $machine_name = str_replace('-', '_', $machine_name);

  // Check if custom directory exists.
  if (!is_dir(drush_get_context('DRUSH_DRUPAL_ROOT') . '/themes/custom')) {
    mkdir(drush_get_context('DRUSH_DRUPAL_ROOT') . '/themes/custom', 0755, TRUE);
  }

  $neato_path    = drush_get_context('DRUSH_DRUPAL_ROOT') . '/' . drupal_get_path('theme', 'neato');
  $subtheme_path = drush_get_context('DRUSH_DRUPAL_ROOT') . '/themes/custom/' . $machine_name;

  // Make a fresh copy of the subtheme.
  $s = drush_copy_dir("$neato_path/STARTER/", $subtheme_path);
  if (empty($s)) {
    return;
  }

  // Rename files and fill in the theme machine name.
  drush_op('rename', "$subtheme_path/STARTER.info.yml.txt", "$subtheme_path/$machine_name.info.yml");
  drush_op('rename', "$subtheme_path/STARTER.libraries.yml", "$subtheme_path/$machine_name.libraries.yml");
  drush_op('rename', "$subtheme_path/STARTER.theme", "$subtheme_path/$machine_name.theme");
  drush_op('rename', "$subtheme_path/css/STARTER.css", "$subtheme_path/css/$machine_name.css");
  drush_op('rename', "$subtheme_path/scss/STARTER.scss", "$subtheme_path/scss/$machine_name.scss");
  drush_op('rename', "$subtheme_path/templates/base/page.html.twig.txt", "$subtheme_path/templates/base/page.html.twig");

  // Update the .info file.
  drush_op('neato_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'Neato Starter', "$name");

  // Change the description of the theme.
  if (!empty($description)) {
    drush_op('neato_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'Custom sub-theme based on the the Neato base theme.', "$description");
  }

  // Replace STARTER in info.yml.
  drush_op('neato_file_str_replace', "$subtheme_path/$machine_name.info.yml", 'STARTER', "$machine_name");

  // Rename functions in .theme.
  drush_op('neato_file_str_replace', "$subtheme_path/$machine_name.theme", 'STARTER', "$machine_name");

  // Replace STARTER in package.json.
  drush_op('neato_file_str_replace', "$subtheme_path/package.json", 'STARTER', "$machine_name");

  // Replace STARTER in gulpfile.js.
  drush_op('neato_file_str_replace', "$subtheme_path/gulpfile.js", 'STARTER', "$machine_name");

  // Replace THEMENAME in STARTER.scss.
  drush_op('neato_file_str_replace', "$subtheme_path/scss/$machine_name.scss", 'THEMENAME', "$machine_name");

  // Replace STARTER in the libraries.yml.
  drush_op('neato_file_str_replace', "$subtheme_path/$machine_name.libraries.yml", 'STARTER', "$machine_name");

  // Notify user of the newly created theme.
  drush_print(dt("\n!name sub-theme was created in !path. \n",
    [
      '!name' => $name,
      '!path' => $subtheme_path,
    ]
  ));
}

/**
 * Internal helper: Replace strings in a file.
 */
function neato_file_str_replace($file_path, $find, $replace) {
  $file_contents = file_get_contents($file_path);
  $file_contents = str_replace($find, $replace, $file_contents);
  file_put_contents($file_path, $file_contents);
}

/**
 * Implements hook_drush_help().
 */
function neato_drush_help($section) {
  switch ($section) {
    case 'drush:neato-sub-theme':
      return dt("Create a sub-theme based on Neato.");
  }
}
