<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Provider {

  public function __construct($core, $url) {
    $this->core = $core;
    $this->url  = $url;

    $this->init();
  }

  protected function init() {}
}
