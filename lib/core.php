<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use A;
use C;
use Embed\Embed;
use F;

class Core {

  public function __construct($url, $args = []) {
    $this->url      = $url;
    $this->cache    = new Cache($url);
    $this->options  = $this->options($args);

    $this->load();
  }

  // ================================================
  //  Load remote or cached data
  // ================================================

  protected function load() {
    if($this->cache->exists()) {
      $this->data = $this->cache->get();
    } else {
      $this->data = Embed::create($this->url, [
        'adapter' => [
          'config' => [
            'getBiggerImage' => true,
          ]
        ]
      ]);
      $this->cache->set($this->data, 1560);
    }
  }

  // ================================================
  //  Default options
  // ================================================

  protected function options($options) {
    $defaults = [
      'autoplay'  => c::get('plugin.oembed.video.autoplay', false),
      'lazyvideo' => c::get('plugin.oembed.video.lazyload', true),
    ];

    return a::merge($defaults, $options);
  }


  // ================================================
  //  Load cached thumb (or cache it if not yet)
  // ================================================

  public function thumb() {
    $thumb  = $this->image();
    $dir    = kirby()->roots()->thumbs() . DS . 'oembed';
    $file   = md5($thumb) . '.' . pathinfo($thumb, PATHINFO_EXTENSION);
    $cached = $dir . DS . $file;

    if(f::modified($cached) + 86400 < time()) {
      f::remove($cached);
    }

    if(!f::exists($cached)) {
      if(!file_exists($dir)) mkdir($dir);
      file_put_contents($cached, file_get_contents($thumb));
    }

    return url('thumbs/oembed/' . $file);
  }


  // ================================================
  //  Magic methods
  // ================================================

  public function __call($name, $args) {
    $class = 'Kirby\Plugins\distantnative\oEmbed\Providers\\' . $this->data()->providerName;

    if(method_exists($class, $name)) {
      return $class::{$name}($this, $args);
    } else {
      return $this->data()->{$name};
    }
  }

  public function data() {
    return is_array($this->data) ? $this->data[0] : $this->data;
  }

  public function __toString() {
    return (string)new Html($this);
  }
}
