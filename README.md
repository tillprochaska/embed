![oEmbed for Kirby CMS](http://distantnative.com/remote/github/kirby-oembed-github.png)  
[![Release](https://img.shields.io/github/release/distantnative/oembed.svg)](https://github.com/distantnative/oembed/releases) [![Issues](https://img.shields.io/github/issues/distantnative/oembed.svg)](https://github.com/distantnative/oembed/issues) [![License](https://img.shields.io/badge/license-GPLv3-blue.svg)](https://raw.githubusercontent.com/distantnative/oembed/master/LICENSE)

This plugin extends [Kirby 2 CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities. It uses [Essence](https://github.com/felixgirault/essence) and [Multiplayer](https://github.com/felixgirault/multiplayer/) as PHP wrappers for oEmbed.

Using this plugin enables Kirby 2 CMS to display embeds of several media sites (e.g. YouTube, Vimeo, Soundcloud) by only providing the URL to the medium. The plugin also includes some [options](#options) to reduce the site loading time by using lazy videos (thumbnail preview and embed is only loaded after click) as well as extensive caching.

**Requires:** PHP 5.5 and higher (looking into a more compatible solution for older PHP versions)

![In the panel](http://distantnative.com/remote/github/kirby-oembed-github-example1.png)

![On the front](http://distantnative.com/remote/github/kirby-oembed-github-example2.png)


# Table of Contents
1. [Installation](#Installation)
2. [Usage](#Usage)
3. [Options](#Options)
4. [Examples](#Usage)
5. [Help & Improve](#Help)
6. [Version History](#VersionHistory)


# Installation <a id="Installation"></a>
1. Download [oEmbed](https://github.com/distantnative/oembed/zipball/master/)
2. Copy the files to `site/plugins/oembed/` 
3. Copy the contents of `assets` to `assets/oembed/`
4. Add CSS link to your header (e.g. `site/snippets/header.php`):
```php
echo css('assets/oembed/oembed.css');
```

**If lazy video [option](#Options) is active:**    
5. Add the following JS links to your header (e.g. `site/snippets/header.php`):
```php
echo js(array(
  '//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js', // requires jQuery
  'assets/oembed/oembed.min.js'
);
```

Instead of including additional CSS and JS links inside your header, you can also include the contents of `assets/oembed.css` and `assets/oembed.js` in your existing CSS and JS files.

### Update
1. Replace the `site/plugins/oembed` and  `assets/oembed` directories with recent version
2. Delete `site/cache/oembed` and `thumbs/oembed`


# Usage <a id="Usage"></a>
There are two ways to use oEmbed:

**Inside Kirbytext:**  
Use the Kirbytag `(oembed: url)` with the url referring to a supported medium (e.g. YouTube, Vimeo, Soundcloud).
```
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.
(oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
```

**Inside templates:**  
Use the field method `->oembed()` on fields that contain an url reffering to a supported medium (e.g. YouTube, Vimeo, Soundcloud).
```php
<?php echo $page->featured_video()->oembed(); ?>
```


# Options <a id="Options"></a>
There are a few options you can set globally for Kirby oEmbed in `site/config/config.php`:
```php
c::set('oembed.autoplay', true);
c::set('oembed.lazyvideo', true);
c::set('oembed.caching', false);
c::set('oembed.cacheexpires', 3600*24);
```
- **oembed.autoplay**:  
Videos start playing automatically after page loaded. Can also be used on a per-tag basis: `(oembed: https://youtube.com/watch?v=wZZ7oFKsKzY autoplay: true)`
- **oembed.lazyvideo**:  
Only after clicking on the thumbnail, the embed (iframe, object) is loaded (default: false)
- **oembed.caching**:  
Enable/disable caching of embed HTML and video thumbnails (default: false)
- **oembed.cacheexpires**:  
Duration after the cached thumbnails expire in seconds (default: 3600)

### Optional parameters
There are a few optional parameters for some media sites. For the Kirbytext tag you can use them in the following way:
 
```
(oembed: https://youtube.com/watch?v=wZZ7oFKsKzY size: smaller)
```

And for the field method `->oembed()`:
```php
<?php echo $page->featured_video()->oembed(array('size' => 'smaller')); ?>
```

The following parameters are available so far:
- **YouTube**
    - jsapi (true/false)
- **Vimeo**
    - jsapi (true/false)
- **SoundCloud**
    - size (default/smaller/compact)
    - visual (true/false)
    - artwork (true/false)

You can set these parameters also globally for all oEmbed Kirbytext tags that do not specifiy the parameter themselves in `site/config/config.php`:
```php
c::get('oembed.defaults.visual', 'true');
c::get('oembed.defaults.artwork', 'true');
c::get('oembed.defaults.size', 'compact');
```


# Examples <a id="Examples"></a>
### Blog: Featured Video
Embed featured videos to your blog posts. The URL to the video (e.g. on YouTube or Vimeo) is stored in a field called ´video´ in this example.
```php
// site/snippets/article.php
<article>
  <aside class="entry-meta">...</aside>
  <div class="entry-main">
    <?php if($post->video()!=''): ?>
      <figure class="entry-cover"><?php echo $post->video()->oembed(); ?></figure>
    <?php endif; ?>
    <div class="entry-content"><?php echo $post->text()->kt(); ?></div>
  </div>
</article>
```


# Help & Improve <a id="Help"></a>
*If you have any suggestions for further configuration options, [please let me know](https://github.com/distantnative/oembed/issues/new).*


# Version history <a id="VersionHistory"></a>
**1.0**
- Restructured plugin files and renamed repository to `oembed`
- Updated Essence library to v3
- Added custom class option and default container classes
- Added `jsapi` option
- Improved frameborder handling and validation
- Better thumb caching and low res fallback
- Better cache and thumb dir handling
- Autoplay only on lazyload or with `autoplay` option
- Enhanced CSS browser support

**0.7**
- File structure of plugin repository changed
- Improved HTML validation of plugin output

