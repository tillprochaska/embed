<?php

class OembedField extends UrlField {

  public static $assets = [
    'css' => [
      'oembed.css',
      'field.css'
    ],
    'js' => [
      'oembed.js',
      'field.js'
    ]
  ];

  public function __construct() {
    parent::__construct();

    $this->type        = 'oembed';
    $this->icon        = 'object-group';
  }

  public function input() {
    $input = parent::input();
    $input->data('field', 'oembedfield');
    $input->data('ajax', url('api/plugin/oembed/preview'));
    return $input;
  }

  public function template() {
    $template = parent::template();
    $template->append(tpl::load(__DIR__ . DS . 'oembed.html.php'));
    return $template;
  }

}
