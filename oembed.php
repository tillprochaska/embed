<?php

require 'core.php';

/**
 * oEmbed field method: $page->video()->oembed()
 */
field::$methods['oembed'] = function($field, $args = array()) {
  $oembed = new KirbyOEmbed($field->value);
  if (isset($args['thumbnail'])) $oembed->setThumbnail($args['thumbnail']);
  return $oembed->get($args);
};


/**
 * oEmbed Kirbytext tag: (oembed: https://youtube.com/watch?v=wZZ7oFKsKzY)
 */
kirbytext::$tags['oembed'] = array(
  'attr' => array(
      'thumb',
      'artwork',
      'visual',
      'size',
      'color',
  ),
  'html' => function($tag) {
    $args = array(
      "artwork" => $tag->attr('artwork', c::get('oembed.defaults.artwork', 'true')),
      "visual"  => $tag->attr('visual', c::get('oembed.defaults.visual', 'true')),
      "size"    => $tag->attr('size', c::get('oembed.defaults.size', 'default')),
      "color"   => $tag->attr('color', c::get('oembed.defaults.color', ''))
    );

    $oembed = new KirbyOEmbed($tag->attr('oembed'));
    if ($tag->attr('thumb', false)) $oembed->setThumbnail($tag->file($tag->attr('thumb'))->url());
    return $oembed->get($args);
  }
);


