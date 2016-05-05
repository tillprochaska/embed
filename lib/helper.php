<?php

function oembed($url) {
  return new Kirby\Plugins\distantnative\oEmbed\Core($field->value);
}
