![oEmbed for Kirby CMS](docs/logo.png)  
[![Release](https://img.shields.io/github/release/distantnative/oembed.svg)](https://github.com/distantnative/oembed/releases) [![Issues](https://img.shields.io/github/issues/distantnative/oembed.svg)](https://github.com/distantnative/oembed/issues) [![Moral License](https://img.shields.io/badge/buy-moral_license-8dae28.svg)](https://gumroad.com/l/kirby-oembed)

The oEmbed plugin extends [Kirby CMS](http://getkirby.com) with some extensive embed functionalities. It enables Kirby to display embeds of several media sites (e.g. YouTube, Vimeo, Soundcloud) by only providing the URL to the medium. The plugin also includes some [options](#Options) to reduce the site loading time by using lazy videos as well as extensive caching.

It is built on the [oscarotero/Embed](https://github.com/oscarotero/Embed) library.

**Requires:** PHP 5.4+ (looking into a more compatible solution)


## Table of Contents
1. [Requirements](#Requirements)
2. [Installation & Update](#Installation)
3. [Usage](#Usage)
4. [Options](#Options)
5. [Examples](#Usage)
6. [Help & Improve](#Help)
7. [Version History](#VersionHistory)

## Requirements <a id="Requirements"></a>
Since version 1.0.0 the footnotes plugin requires Kirby CMS 2.3.0 or higher.  
If you are running an older version of Kirby, please use [version 0.9.0](https://github.com/distantnative/footnotes/releases/tag/v0.9).


## Installation & Update <a id="Installation"></a>
1. Download [oEmbed](https://github.com/distantnative/oembed/zipball/master/)
2. Add the files to `site/plugins/oembed/` 
3. Add CSS link to your header (e.g. `site/snippets/header.php`):
```php
<?= css('assets/plugins/oembed/css/oembed.css') ?>
```

#### With video lazyload [option](#Options)
4. Add the following JS link to your footer (e.g. `site/snippets/footer.php`):
```php
<?= js('assets/plugins/oembed/js/oembed.js') ?>
```

#### With the [Kirby CLI](https://github.com/getkirby/cli)
```
kirby plugin:install distantnative/oembed
```


## Usage <a id="Usage"></a>
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


## Options <a id="Options"></a>
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
- **all providers''
    - class
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


## Examples <a id="Examples"></a>
#### Blog: Featured Video
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

![In the panel](http://distantnative.com/remote/github/kirby-oembed-github-example1.png)

![On the front](http://distantnative.com/remote/github/kirby-oembed-github-example2.png)

![On the front playing](http://distantnative.com/remote/github/kirby-oembed-github-example3.png)


## Help & Improve <a id="Help"></a>
*If you have any suggestions for further configuration options, [please let me know](https://github.com/distantnative/oembed/issues/new).*


## Version history <a id="VersionHistory"></a>
You can find a more or less complete version history in the [changelog](docs/CHANGELOG.md).

## License
[MIT License](http://www.opensource.org/licenses/mit-license.php)

## Author
Nico Hoffmann - <https://nhoffmann.com>
