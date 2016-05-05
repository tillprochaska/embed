<?php

namespace Kirby\Plugins\distantnative\oEmbed;

require_once('vendor/Embed/autoloader.php');
require_once('lib/core.php');
require_once('lib/cache.php');
require_once('lib/helper.php');


// $page->video()->oembed()
$kirby->set('field::method', 'oembed', function($field, $args = []) {
  return new Core($field->value);
});


// (oembed: …)
$kirby->set('tag', 'oembed', [
  'attr' => [],
  'html' => function($tag) {
    return new Core($tag->attr('oembed'));
  }
]);
