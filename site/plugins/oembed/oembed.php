<?php
/**
 * Kirby oEmbed plugin for Kirby 2
 *
 * @author: Nico Hoffmann - distantnative.com
 * @version: 0.1
 *
 */

require('Embera/Autoload.php');


/**
 * Extracts the YouTube ID from an URL
 * @param string    The url from where the ID should be extracted.
 * @return string   The ID extracted from the URL - if not possible false
 */
function youtube_id_from_url($url) {
  $pattern =
    '%^# Match any youtube URL
    (?:https?://)?  # Optional scheme. Either http or https
    (?:www\.)?      # Optional www subdomain
    (?:             # Group host alternatives
      youtu\.be/    # Either youtu.be,
    | youtube\.com  # or youtube.com
      (?:           # Group path alternatives
        /embed/     # Either /embed/
      | /v/         # or /v/
      | .*v=        # or /watch\?v=
      )             # End path alternatives.
    )               # End host alternatives.
    ([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
    ($|&).*         # if additional parameters are also in query string after video id.
    $%x'
    ;
    $result = preg_match($pattern, trim($url), $matches);
    if (false !== $result)
      return $matches[1];
    return false;
}


/**
 * Converts a media URL into an embed (oEmbed)
 * @param string    The URL that will be converted
 * @return string   The HTML with the embed (iframe, object)
 */
function oembed_convert($url) {
  $embera = new \Embera\Embera();
  $embera = new \Embera\Formatter($embera);
  $url_info = $embera->getUrlInfo($url);

  // For video embeds
  if ($url_info[$url]['type'] == 'video') :
    $embera->setTemplate('{html}');
    $embed = $embera->transform($url);

    if (c::get('oembed.lazyvideo', false)) :
      // Add Custom Parameters to embed URL (e.g. autoload)
      // YouTube
      if ($url_info[$url]['provider_name'] == 'YouTube')
        $embed = str_replace('?feature=oembed', '?feature=oembed'.'&amp;'.'autoplay=1'.'&amp;'.'rel=0'.'&amp;'.'showinfo=0', $embed);
      // Vimeo
      elseif ($url_info[$url]['provider_name'] == 'Vimeo')
        $embed = str_replace('" width="', '?'.'autoplay=1'.'&amp;'.'color='.c::get('oembed.color','aad450').'&amp;'.'byline=0'.'&amp;'.'title=0'.'" width="', $embed);

      $embed = str_replace(' src="', ' data-src="', $embed);


      // Get thumbnail with higher resolution for YouTube
      $youtube_maxres_thumb = youtube_id_from_url($url);
      if ($youtube_maxres_thumb)
        $thumb = "http://i1.ytimg.com/vi/".$youtube_maxres_thumb."/maxresdefault.jpg";
      else
        $thumb = "{thumbnail_url}";

      $embera->setTemplate('<img src="'.$thumb.'" class="thumb">');
      $output = new Brick('div');
      $output->addClass('oembed-video');
      $output->append($embera->transform($url));
      $output->append($embed);
    else:
      $output = $embed;
    endif;

  // For non-video embeds
  else :
    $embera->setTemplate('{html}');
    $output = $embera->transform($url);
  endif;

  return $output;
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
