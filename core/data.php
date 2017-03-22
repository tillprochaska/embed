<?php

namespace Kirby\distantnative\oEmbed;

use C;
use Embed\Embed;

class Data {

  public static function get($url) {
    try {
      return Embed::create($url, static::config());
    } catch (\Exception $e) {
      return false;
    }
  }

  protected static function config() {
    return [
      'choose_bigger_image' => true,
      'google' => [
        'key' => c::get('plugin.oembed.providers.google.key', null)
      ],
      'soundcloud' => [
        'key' => c::get('plugin.oembed.providers.soundcloud.key', null)
      ]
    ];
  }

}
