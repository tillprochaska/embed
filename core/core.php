<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use A;
use C;

class Core {

  public function __construct($url, $args = []) {
    $this->url      = $url;
    $this->cache    = new Cache($url);

    $this->load();

    if($this->data !== false) {
      $this->provider   = $this->provider();
      $this->url        = new Url($this->data()->code);
      $this->options    = $this->options($args);
    }
  }

  // ================================================
  //  Load remote or cached data
  // ================================================

  protected function load() {
    if($this->cache->exists() && c::get('plugin.oembed.caching', true)) {
      $this->data = $this->cache->get();
    } else {
      $this->data = Data::get($this->url);

      if($this->data && c::get('plugin.oembed.caching', true)) {
        $this->cache->set($this->data, c::get('plugin.oembed.caching.duration', 24) * 60);
      }
    }
  }

  // ================================================
  //  Default options
  // ================================================

  protected function options($options) {
    $defaults = [
      'class'     => null,
      'thumb'     => null,
      'autoplay'  => c::get('plugin.oembed.video.autoplay', false),
      'lazyvideo' => c::get('plugin.oembed.video.lazyload', true),
      'jsapi'     => c::get('plugin.oembed.providers.jsapi', false),
    ];

    return a::merge($defaults, $options);
  }


  // ================================================
  //  Thumb
  // ================================================

  public function thumb() {
    $thumb = $this->options['thumb'] ? $this->options['thumb'] : $this->image();
    return new Thumb($thumb);
  }


  // ================================================
  //  Custom provider instance
  // ================================================

  protected function provider() {
    $namespace = 'Kirby\Plugins\distantnative\oEmbed\Providers\\';
    $class     =  $namespace . $this->data()->providerName;
    $class     =  class_exists($class) ? $class : $namespace . 'Provider';
    return new $class($this, $this->url);
  }


  // ================================================
  //  Magic methods
  // ================================================

  public function __call($name, $args) {
    if(method_exists($this->provider, $name)) {
      return $this->provider->{$name}($this->data()->{$name}, $args);
    } else {
      return $this->data()->{$name};
    }
  }

  public function data() {
    return is_array($this->data) ? $this->data[0] : $this->data;
  }

  public function __toString() {
    if($this->data === false) {
      return Html::error($this->url);
    }

    return (string)new Html($this);
  }
}
