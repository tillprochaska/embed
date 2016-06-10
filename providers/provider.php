<?php

namespace Kirby\Plugins\distantnative\oEmbed\Providers;

class Provider {

  public function __construct($core, $url) {
    $this->core = $core;
    $this->url  = $url;

    $this->init();
  }

  protected function init() {}

  public function providerParameters() {
    return;
  }


  // ================================================
  //  Helpers
  // ================================================

  protected function option($option) {
    return $this->core->options[$option];
  }

  protected function parameter($parameter) {
    return $this->core->url->parameter($parameter);
  }
}
