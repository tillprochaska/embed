<?php

namespace Kirby\Plugins\distantnative\oEmbed;

$paths = [
  // Vendor
  '../vendor/Embed/src/autoloader',

  // Core
  'core',
  'data',
  'cache',
  'url',
  'html',
  'thumb',

  // Providers
  'providers/provider',
  'providers/youtube',
  'providers/vimeo',
];

load($paths);

function load($paths) {
  foreach($paths as $path) {
    $path = str_replace('/', DS, $path);
    require_once(__DIR__ . DS . $path . '.php');
  }
}
