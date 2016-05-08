<?php

namespace Kirby\Plugins\distantnative\oEmbed;

use L;
use Tpl;

class Html {

  public function __construct($core) {
    $this->core    = $core;
    $this->options = $this->core->options;

    $this->data    = [
      'code'     => $this->core->code(),
      'class'    => $this->core->options['class'],
      'type'     => $this->core->type(),
      'provider' => $this->core->providerName(),
      'style'    => null,
      'more'     => null
    ];
  }


  // ================================================
  //  Outputs
  // ================================================

  public function __toString() {
    // call preparation method for type
    $this->prepareType();

    // update embed ifram url
    $this->updateData('code', function($code) {
      return $this->core->url->update($code);
    });

    return $this->snippet('wrapper', $this->data);
  }

  public static function error($url, $msg = null) {
    if(!$msg) $msg = 'noembed';
    $msg  = l::get('plugin.oembed.error.' . $msg);
    $path = dirname(__DIR__) . DS . 'snippets' . DS . 'error.php';

    return tpl::load($path, [
      'url' => $url,
      'msg' => $msg
    ]);
  }

  // ================================================
  //  Types
  // ================================================

  protected function prepareType() {
    $prepareType = 'prepare' . ucfirst($this->core->type());
    if(method_exists($this, $prepareType)) {
      $this->{$prepareType}();
    }

    if(!$this->data['code']) {
      $this->data['code'] = $this->error($this->core->input, 'nocode');
    }
  }


  // ================================================
  //  Videos
  // ================================================

  protected function prepareVideo() {
    // Container ratio
    $this->data['style'] = 'padding-top:'.$this->core->aspectRatio().'%';

    // Lazy video
    if($this->options['lazyvideo']) {
      $this->lazyVideo();
    }
  }

  protected function lazyVideo() {
    // src -> data-src
    $this->updateData('code', function($code) {
      $pattern = '/(iframe.*)(src)(=".*")/U';
      $replace = '$1data-src$3';
      return preg_replace($pattern, $replace, $code);
    });

    // thumb
    $this->data['more'] = $this->snippet('thumb', [
      'url'   => $this->core->thumb(),
    ]);
  }

  // ================================================
  //  Links
  // ================================================

  protected function prepareLink() {
    if(!$this->data['code']) {
      $this->updateData('code', $this->snippet('typeLink', [
        'url'  => $this->core->url(),
        'text' => $this->core->title()
      ]));
    }
  }


  // ================================================
  //  Helpers
  // ================================================

  protected function updateData($data, $value) {
    if(is_callable($value)) {
      $this->data[$data] = $value($this->data[$data]);
    } else {
      $this->data[$data] = $value;
    }
  }

  protected function snippet($name, $data) {
    return tpl::load(dirname(__DIR__) . DS . 'snippets' . DS . $name . '.php', $data);
  }

}
