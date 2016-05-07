<?php

require_once('lib/autoload.php');

$kirby = kirby();

// ================================================
//  Global helper
// ================================================

function oembed($url, $args = []) {
  return new Kirby\Plugins\distantnative\oEmbed\Core($url, $args);
}


// ================================================
//  $page->video()->oembed()
// ================================================

$kirby->set('field::method', 'oembed', function($field, $args = []) {
  return oembed($field->value, $args);
});


// ================================================
//  (oembed: …)
// ================================================

$options = [
  'class'     => 'string',
  'thumb'     => 'string',
  'autoload'  => 'bool',
  'lazyvideo' => 'bool',
  'jsapi'     => 'bool',
];

$kirby->set('tag', 'oembed', [
  'attr' => array_keys($options),
  'html' => function($tag) use($options) {
    $args = [];

    foreach($options as $option => $mode) {
      if($mode === 'bool') {
        if($tag->attr($option) === 'true')  $args[$option] = true;
        if($tag->attr($option) === 'false') $args[$option] = false;
      } elseif ($mode === 'string') {
        $args['option'] = $tag->attr($option);
      }
    }

    return oembed($tag->attr('oembed'), $args);
  }
]);


// ================================================
//  Register panel field
// ================================================

$kirby->set('field', 'oembed', __DIR__ . DS . 'field');
$kirby->set('route', [
  'pattern' => 'api/plugin/oembed/preview',
  'action'  => function() {
    return response::json([(string)oembed(get('url'))]);
  },
  'method'  => 'POST'
]);
