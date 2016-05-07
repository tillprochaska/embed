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

    $this->translations();
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

  protected function translations() {
    $root = dirname(__DIR__) . DS . 'translations' . DS;
    require($root . 'en.php');
    if(file_exists($root . panel()->language()->code() . '.php')) {
      require($root . panel()->language()->code() . '.php');
    }
  }

}
