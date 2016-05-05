<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use Embed\Embed;

class Data {

  protected $dir;

  public function __construct($url) {
    $this->data = Embed::create($url, $this->config());
  }

  public function get() {
    return $this->data;
  }

  protected function config() {
    return [
      'adapter' => [
        'config' => [
          'getBiggerImage' => true,
        ]
      ]
    ];
  }

}
