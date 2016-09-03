<?php

namespace Kirby\Plugins\distantnative\oEmbed {

  require_once('core/lib/autoloader.php');

  $kirby    = kirby();
  $language = $kirby->site()->language();

  Autoloader::load([
    'vendor'         => ['Embed/src/autoloader'],
    'translations'   => ['en', $language ? $language->code() : null],
    'core'           => ['core', 'url', 'html'],
    'core/lib'       => ['data', 'cache', 'thumb'],
    'core/providers' => ['provider', true],
  ]);

  include('registry/field-method.php');
  include('registry/tag.php');
  include('registry/field.php');
  include('registry/route.php');

}


// ================================================
//  Global helper
// ================================================

namespace {
  function oembed($url, $args = []) {
    return new Kirby\Plugins\distantnative\oEmbed\Core($url, $args);
  }
}
