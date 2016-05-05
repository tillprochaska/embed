<?php

require_once('vendor/Embed/autoloader.php');
require_once('lib/core.php');
require_once('lib/data.php');
require_once('lib/cache.php');
require_once('lib/html.php');
require_once('lib/thumb.php');

require_once('lib/providers/provider.php');
require_once('lib/providers/youtube.php');


// $page->video()->oembed()
$kirby->set('field::method', 'oembed', function($field, $args = []) {
  return oembed($field->value, $args);
});

// (oembed: …)
$kirby->set('tag', 'oembed', [
  'attr' => [],
  'html' => function($tag) {
    return oembed($tag->attr('oembed'));
  }
]);

// Helper
function oembed($url) {
  return new Kirby\Plugins\distantnative\oEmbed\Core($url);
}
