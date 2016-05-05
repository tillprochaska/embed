<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use Embed\Embed;

class Data {

  protected $dir;

  public function __construct($url) {
    return Embed::create($url, $this->config());
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
