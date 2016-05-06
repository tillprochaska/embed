<?php

namespace Kirby\Plugins\distantnative\oEmbed;

Autoloader::load([

  // Vendor
  'vendor' => [
    'Embed/src/autoloader',
  ],

  // Core
  'core' => [
    'oembed',
    'url',
    'html',
  ],

  // Libraries
  'lib' => [
    'data',
    'cache',
    'thumb',
  ],

  // Providers
  'providers' => [
    'provider',
    'youtube',
    'vimeo',
  ]
]);


class Autoloader {
  public static function load($paths) {
    foreach($paths as $group => $files) {
      if(!is_array($files)) {
        static::loadFile($group);
      } else {
        foreach($files as $file) {
          static::loadFile($group . DS . $file);
        }
      }
    }
  }

  protected static function loadFile($path) {
    require_once(dirname(__DIR__) . DS . str_replace('/', DS, $path) . '.php');
  }
}
