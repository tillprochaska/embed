<?php

require_once('lib/autoload.php');

// ================================================
//  $page->video()->oembed()
// ================================================
$kirby->set('field::method', 'oembed', function($field, $args = []) {
  return oembed($field->value, $args);
});

// ================================================
//  (oembed: …)
// ================================================
$kirby->set('tag', 'oembed', [
  'attr' => [],
  'html' => function($tag) {
    return oembed($tag->attr('oembed'));
  }
]);

// ================================================
//  Global helper
// ================================================
function oembed($url) {
  return new Kirby\Plugins\distantnative\oEmbed\Core($url);
}
