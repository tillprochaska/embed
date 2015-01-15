<?php
/**
 * Kirby oEmbed plugin for Kirby 2
 *
 * @author: Nico Hoffmann - distantnative.com
 * @version: 0.1
 *
 */

require_once('lib/bootstrap.php');
require_once('lib/Multiplayer.php');

/**
 * Converts a media URL into an embed (oEmbed)
 * @param string      The URL that will be converted
 * @param true/false  Will the object be placed inline with text
 * @return string     The HTML with the embed (iframe, object)
 */
function oembed_convert($text) {
  $Essence = Essence\Essence::instance();
  $Multiplayer = new Multiplayer\Multiplayer( );
  $oEmbed = $Essence->embed($text, [
      'thumbnailFormat' => 'maxres'
  ]);

  if ($oEmbed) :
      // Create oembed-video wrapper
      $htmlOutput = new Brick('div');

      if ($oEmbed->type === 'video') :
        $htmlOutput->addClass('oembed-video');
        $WrapperRatio = ($oEmbed->height / $oEmbed->width) * 100;
        $htmlOutput->attr('style','padding-top:'.$WrapperRatio.'%');

        if (c::get('oembed.lazyvideo', false))
          $htmlOutput->addClass('oembed-lazyvideo');

        // Create thumb image
        $htmlThumb = '<img src="'.$oEmbed->thumbnailUrl.'" class="thumb">';

        // Create play button overlay
        $htmlPlay = new Brick('div');
        $htmlPlay->addClass('play');
        $htmlPlay->append('<img src="'.url('assets/oembed/oembed-play.png').'">');

        // Add elements to wrapper
        $htmlOutput->append($htmlPlay);
        $htmlOutput->append($htmlThumb);

        // Create embed HTML
        $htmlEmbed = $Multiplayer->html($oEmbed->url, [
          'autoPlay' => true,
          'highlightColor' => 'BADA55'
        ]);
        $htmlEmbed = str_replace(' src="', ' data-src="', $htmlEmbed);

      else:
        $htmlEmbed = $oEmbed->html;
      endif;

      // Add embed HTML to wrapper
      $htmlOutput->append($htmlEmbed);

      return $htmlOutput;
  else :
    return $text;
  endif;
}


/**
 * Adding an oEmbed field method: e.g. $page->video()->oembed()
 */
field::$methods['oembed'] = function($field) {
  return oembed_convert($field->value);
};


/**
 * Extending Kirbytext with an oEmbed tag: e.g.
 * (oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
 */
kirbytext::$tags['oembed'] = array(
  'html' => function($tag) {
    return oembed_convert($tag->attr('oembed'));
  }
);

