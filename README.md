Kirby oEmbed
============

This plugin extends [Kirby CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities. 
[Embera](https://github.com/mpratt/Embera) is used as a PHP wrapper for oEmbed.

# Installation
1. Copy the ´oembed´ folder to ´site/plugins/´
2. Add to your CSS for fluid + lazy videos:
```
.oembed-video {
  width: 100%;
  position: relative;
  padding: 0;
  padding-top: 56.25%;
  background-color: #dedede;
}

.oembed-video iframe,
.oembed-video object {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
}

.oembed-video .thumb {
  cursor: pointer;
}

.oembed-video iframe,
.oembed-video object {
  display: none;
}
```
3. Add to your JS for lazy videos (jQuery required):
```
$(function() {
  $('.oembed-video .thumb').click(function() {
    wrapper = $(this).parent();
    embed = wrapper.find('iframe, object');
    embed.attr('src', embed.attr('data-src'));
    embed.css({'display' : 'block'});
    wrapper.find('.thumb').remove();
  });
});
```

# Usage
There are two way to use Kirby oEmbed:

**Inside (Kirbytext) fields:**
Include ´(oembed: LINK)´ inside your Kirbytext. The link has to point to a supported media (e.g. YouTube, Vimeo, Soundcloud).

**In templates:**
Use the field method ´->oembed()´ on fields that contain the link to the supported media (e.g. YouTube, Vimeo, Soundcloud).
