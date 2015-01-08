Kirby oEmbed v0.3
============

This plugin extends [Kirby 2 CMS](http://getkirby.com) with some basic [oEmbed](http://oembed.com) functionalities.  
It uses [Embera](https://github.com/mpratt/Embera) as a PHP wrapper for oEmbed.

Using this plugin enables Kirby 2 CMS to display embeds of several media sites (e.g. YouTube, Vimeo, Soundcloud) by only providing the URL to the medium. The plugin also includes some [options](#options) to reduce the site loading time by using lazy videos (thumbnail preview and embed is only loaded after click) as well as thumbnail and embed HTML caching.

# Installation
1. Download [Kirby oEmbed](https://github.com/distantnative/kirby-oembed/zipball/master/)
2. Copy the `site/plugins/oembed` directory to `site/plugins/`
3. Copy the `assets/oembed` directory to `assets/`
4. Add CSS link to your header:
```php
// site/snippets/header.php
echo css('assets/oembed/oembed.css');
```

**If lazy video [option](#options) is active:**  
5. Add the following JS links to your header:
```php
// site/snippets/header.php
echo js('//ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js'); // if jQuery isn't included already
echo js('assets/oembed/oembed.min.js');
```

Instead of including additional CSS and JS links inside your header, you can also include the contents of `assets/oembed/oembed.css` or `assets/oembed/oembed.scss` as well as `assets/oembed/oembed.js` in your existing CSS/SCSS and JS files.

# Update
1. Replace the `site/plugins/oembed` and  `assets/oembed` directories with recent version

# Usage
There are two way to use Kirby oEmbed:

**Inside (Kirbytext) fields:**  
Use `(oembed: URL)` inside your Kirbytext. The URL has to point to a supported media (e.g. YouTube, Vimeo, Soundcloud).
```
Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
(oembed: https://www.youtube.com/watch?v=wZZ7oFKsKzY)
```

**In [templates](http://getkirby.com/docs/templates):**  
Use the field method `->oembed()` on fields that contain the link to the supported media (e.g. YouTube, Vimeo, Soundcloud).
```php
<?php echo $page->featured_video()->oembed(); ?>
```

# Options <a id="options"></a>
There are a few options you can set for Kirby oEmbed in `site/config/config.php`:
```php
// site/config/config.php
c::set('oembed.lazyvideo', true);
c::set('oembed.color', 'aad450');
c::set('oembed.caching', false);
c::set('oembed.cacheexpires', 3600);
```
- **oembed.lazyvideo**:  
Only after clicking on the videos thumbnail, the actual embed (iframe, object) is loaded (default: false)
- **oembed.color**:  
Color used to theme some media's (e.g. Vimeo) video elements (default: 'aad450')
- **oembed.caching**:  
Enable/disable caching of oEmbed HTML and video thumbnails (default: false)
- **oembed.cacheexpires**:  
Duration after the cached thumbnails expire in seconds (default: 3600)

# Examples
### Blog: Featured Video
Use Kirby oEmbed to embed featured videos to your blog posts. The URL to the video (e.g. on YouTube or Vimeo) is stored in a field calles ´video´ in this example.
```php
// site/snippets/article.php
<article>
  <aside class="entry-meta">
    ...
  </aside>

  <div class="entry-main">
    <?php if ($post->video()!='') : ?>
      <figure class="entry-cover">
        <?php echo $post->video()->oembed(); ?>
      </figure>
    <?php endif : ?>

    <div class="entry-content">
      <?php echo $post->text()->kirbytext(); ?>
    </div>
  </div>
  
</article>
```

# Troubleshooting
### Broken HTML embed code has been cached
Open the affected pages in the panel and re-save them. The oEmbed cache fields will be removed and (hopefully correctly) re-added the next time they are displayed.

Having more troubles? Please let me know by [opening and issue](https://github.com/distantnative/kirby-oembed/issues/new).
