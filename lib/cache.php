<?php

namespace Kirby\Plugins\distantnative\oEmbed;

class Cache {

  public function __construct($url) {
    $this->key = md5($url);

    $dir = dirname(__DIR__) . DS . 'cache';
    if(!file_exists($dir)) mkdir($dir);

    $this->cache = \cache::setup('file', ['root' => $dir]);

    if($this->cache->expired($this->key)) {
      $this->cache->remove($this->key);
    }
  }

  public function __call($name, $args) {
    return $this->cache->{$name}($this->key, $args);
  }

}
