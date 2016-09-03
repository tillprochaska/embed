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
  //  Custom parameters
  // ================================================

  protected function set($paramenter) {
    if($this->{$paramenter} !== false) {
      $this->parameter($paramenter . '=' . $this->{$paramenter});
    }
  }

  protected function get($paramenter, $pattern) {
    $this->{$paramenter} = preg_match('/' . $paramenter . '=(' . $pattern . ')/', $this->url, $result) ? $result[1] : false;
  }

  protected function getBool($paramenter) {
    $this->get($paramenter, '[0-1]');
  }

  protected function getNumber($paramenter) {
    $this->get($paramenter, '[0-9]*');
  }

  protected function getString($paramenter) {
    $this->get($paramenter, '[a-zA-Z]*');
  }

  protected function getAll($paramenter) {
    $this->get($paramenter, '[a-zA-Z0-9]*');
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
