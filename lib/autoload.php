<?php

namespace Kirby\Plugins\distantnative\oEmbed;

Autoloader::load([

  // Vendor
  'vendor' => [
    'Embed/src/autoloader',
  ],

  // Core
  'core' => [
    'core',
    'url',
    'html',
  ],

  // Libraries
  'lib' => [
    'data',
    'cache',
    'thumb',
  ],

  // Translations
  'translations' => [
    'en',
    kirby()->site()->language() ? kirby()->site()->language()->code() : null
  ],

  // Providers
  'providers' => [
    'provider',
    true
  ]
]);

use Dir;

class Autoloader {
  public static function load($paths) {
    foreach($paths as $group => $files) {

      // single file
      if(!is_array($files)) {
        static::loadFile($group . '.php');

      // directory
      } else {
        foreach($files as $file) {

          // load all files from this directory
          if($file === true) {
            foreach(dir::read(dirname(__DIR__) . DS . $group) as $file) {
              static::loadFile($group . DS . $file);
            }

          // load specified files
          } else {
            static::loadFile($group . DS . $file . '.php');
          }
        }
      }
    }
  }

  protected static function loadFile($path) {
    $file = dirname(__DIR__) . DS . str_replace('/', DS, $path);
    if(file_exists($file)) {
      require_once($file);
    }
  }
}
