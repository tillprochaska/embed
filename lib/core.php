<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use Embed\Embed;

class Core {

  public function __construct($url) {
    $this->url   = $url;
    $this->cache = new Cache($url);

    $this->load();
  }

  protected function load() {
    if($this->cache->exists()) {
      $this->data = $this->cache->get();
    } else {
      $this->data = Embed::create($this->url);
      $this->cache->set($this->data, 1560);
    }
  }

  protected function data() {
    return is_array($this->data) ? $this->data[0] : $this->data;
  }

  public function __call($name, $args) {
    return $this->data()->{$name};
  }

  public function __toString() {
    return $this->code();
  }
}
