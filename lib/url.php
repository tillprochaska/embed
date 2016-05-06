<?php

namespace Kirby\Plugins\distantnative\oEmbed;


class Url {

  public $parameters = [];

  public function __construct($code) {
    preg_match('/(src=")(.*)(")/U', $code, $match);
    $this->url = $match[2];
  }

  public function parameter($new) {
    if(!is_array($new)) $new = [$new];

    $this->parameters = array_merge($this->parameters, $new);
  }

  public function get() {
    $newParameters = implode('&', $this->parameters);
    $hasParameter  = preg_match('/\?/', $this->url);

    return $this->url . ($hasParameter ? '&' : '?') . $newParameters;
  }

  public function update($code) {
    $pattern = '/(src|data-src)(=")(.*)(")/U';
    $order   = '$1$2' . $this->get() . '$4';
    return preg_replace($pattern, $order, $code);
  }

}
