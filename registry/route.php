<?php

$kirby->set('route', [
  'pattern' => 'api/plugin/oembed/preview',
  'action'  => function() {
    $oembed = oembed(get('url'), [
      'lazyvideo' => true
    ]);

    $response = [];

    if($oembed->data === false) {
      $response['success'] = 'false';

    } else {
      $response['success']      = 'true';
      $response['title']        = Kirby\Plugins\distantnative\oEmbed\Html::removeEmojis($oembed->title());
      $response['authorName']   = $oembed->authorName();
      $response['authorUrl']    = $oembed->authorUrl();
      $response['providerName'] = $oembed->providerName();
      $response['providerUrl']  = $oembed->url();
      $response['type']         = ucfirst($oembed->type());
      $response['parameters']   = Kirby\Plugins\distantnative\oEmbed\Html::cheatsheet($oembed->providerParameters());
    }

    if(get('code') === 'true') {
      $response['code'] = (string)$oembed;
    }

    return \response::json($response);
  },
  'method'  => 'POST'
]);
