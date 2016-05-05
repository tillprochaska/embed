<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use A;
use C;

class Core {

  public function __construct($url, $args = []) {
    $this->url      = $url;
    $this->cache    = new Cache($url);

    $this->load();

    $this->provider   = $this->provider();
    $this->options    = $this->options($args);
    $this->parameters = [];
  }

  // ================================================
  //  Load remote or cached data
  // ================================================

  protected function load() {
    if($this->cache->exists()) {
      $this->data = $this->cache->get();
    } else {
      $this->data = new Data($this->url);
      $this->data = $this->data->get();
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
  //  Thumb
  // ================================================

  public function thumb() {
    return new Thumb($this->image());
  }


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
    return (string)new Html($this);
  }
}
