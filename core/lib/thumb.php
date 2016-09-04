<?php

namespace Kirby\distantnative;

use C;
use F;

class Thumb {

  protected $dir;

  public function __construct($plugin, $url, $lifetime) {
    $this->plugin = $plugin;
    $this->lifetime = $lifetime;
    $this->url    = $url;
    $this->dir    = kirby()->roots()->thumbs() . DS . '_plugins' . DS . $this->plugin;

    $this->file = md5($this->url) . '.' . pathinfo(strtok($this->url, '?'), PATHINFO_EXTENSION);
    $this->root = $this->dir . DS . $this->file;

    $this->expired();
    $this->cache();
  }

  protected function expired() {
    if((f::modified($this->root) + $this->lifetime) < time()) {
      f::remove($this->root);
    }
  }

  protected function cache() {
    if(!f::exists($this->root)) {
      if(!file_exists($this->dir)) mkdir($this->dir, 0777, true);
      file_put_contents($this->root, file_get_contents($this->url));
    }
  }

  public function __toString() {
    return url('thumbs/_plugins/' . $this->plugin . '/' . $this->file);
  }


}
