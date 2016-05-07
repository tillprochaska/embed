<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use C;
use F;

class Thumb {

  protected $dir;

  public function __construct($url) {
    $this->url  = $url;
    $this->dir  = kirby()->roots()->thumbs() . DS . '_plugins' . DS . 'oembed';

    $this->file = md5($this->url) . '.' . pathinfo($this->url, PATHINFO_EXTENSION);
    $this->root = $this->dir . DS . $this->file;

    $this->expired();
    $this->cache();
  }

  protected function expired() {
    if(f::modified($this->root) + (c::get('plugin.oembed.caching.duration', 24) * 60 * 60) < time()) {
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
    return c::set('plugin.oembed.caching', true) ? url('thumbs/_plugins/oembed/' . $this->file) : $this->url;
  }


}
