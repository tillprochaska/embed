(function($) {
  $.fn.oembedfield = function() {

    // ================================================
    //  Make icon clickable
    // ================================================

    var clickableIcon = function($this) {
      var icon = $this.next('.field-icon');

      icon.css({
        'cursor': 'pointer',
        'pointer-events': 'auto'
      });

      icon.on('click', function() {
        var url = $.trim($this.val());
        if(url !== '' && $this.is(':valid')) {
          window.open(url);
        } else {
          $this.focus();
        }
      });
    };


    // ================================================
    //  Update embed
    // ================================================

    var updateEmbed = function($this, bucket, label, loading, info) {
      var url = $.trim($this.val());

      if(!$this.data('oembedurl') || url !== $this.data('oembedurl')) {
        $this.data('oembedurl', url);

        if(url === '') {
          hidePreview(bucket, label);
          hideInfo(info);

        } else if($this.is(':valid')) {
          clearPreview(bucket, label, loading);

          $.ajax({
            url:     $this.data('ajax') + 'preview',
            type:    'POST',
            data:    { url: url },
            success: function(data) {
              showPreview(bucket, loading, data);

              if(data.success !== 'false') {
                showInfo(info, data);
              } else {
                hideInfo(info);
              }
            },
          });
        }

      }
    };


    // ================================================
    //  Set preview section
    // ================================================

    var showPreview = function(bucket, loading, data) {
      loading.css('opacity', '0');
      bucket.html(data.code).css('opacity', '1');
      bucket.find('.kirby-plugin-oembed__thumb').click(pluginOembedLoadLazyVideo);
    };

    var clearPreview = function(bucket, label, loading) {
      bucket.add(label).css('opacity', '0');
      loading.css('opacity', '1');
    };

    var hidePreview = function(bucket, label) {
      bucket.css('opacity', '0').html('');
      label.css('opacity', '1');
    };


    // ================================================
    //  Set info section
    // ================================================

    var showInfo = function(info, data) {
      if(data.title) {
        info.title.html(data.title).show();
      } else {
        info.title.hide();
      }

      if(data.authorName) {
        info.author.show().find('a').attr('href', data.authorUrl ).html(data.authorName);
      } else {
        info.author.hide();
      }

      if(data.providerName) {
        info.provider.show().find('a').attr('href', data.providerUrl ).html(data.providerName);
      } else {
        info.provider.hide();
      }

      if(data.type) {
        info.type.show().html(data.type);
      } else {
        info.type.hide();
      }

      if(info.wrapper.prop('style').display === '') {
        info.wrapper.show();
      } else {
        info.wrapper.slideDown();
      }
    };

    var hideInfo = function(info) {
      info.wrapper.slideUp();
    };


    // ================================================
    //  Fix borders
    // ================================================

    var showBorder = function($this, preview, info) {
      if(!$this.parents('.field').hasClass('field-with-error')) {
        setBorder(preview, info, '#8dae28');
      } else {
        setBorder(preview, info, '#000');
      }
    };

    var hideBorder = function(preview, info) {
      setBorder(preview, info, '');
    };

    var setBorder = function(preview, info, color) {
      preview.add(info.wrapper).css('border-color', color);
    };


    // ================================================
    //  Initialization
    // ================================================

    return this.each(function() {

      var $this = $(this);

      if($this.data('oembedfield')) {
        return;
      } else {
        $this.data('oembedfield', true);
      }

      // make icon clickable
      clickableIcon($this);

      // collect all elements
      var inputTimer;
      var preview = $this.parent().nextAll('.field-oembed-preview');
      var bucket  = preview.find('.field-oembed-preview__bucket');
      var label   = preview.find('.field-oembed-preview__label');
      var loading = preview.find('.field-oembed-preview__loading');
      var info    = $this.parent().nextAll('.field-oembed-info');
      info        = {
        wrapper:  info,
        title:    info.find('.field-oembed-info__title'),
        author:   info.find('.field-oembed-info__author'),
        provider: info.find('.field-oembed-info__provider'),
        type:     info.find('.field-oembed-info__type')
      };

      // update embed on input blur
      $this.on('blur', function() {
        window.clearTimeout(inputTimer);
        updateEmbed($this, bucket, label, loading, info);
      });

      // update embed on input change (delayed)
      $this.bind('input embed.change', function() {
        window.clearTimeout(inputTimer);
        inputTimer = window.setTimeout(function(){
          updateEmbed($this, bucket, label, loading, info);
        }, 1000);
      });

      // update embed on load
      updateEmbed($this, bucket, label, loading, info);

      // fix border colors on input focus and blur
      $this.focus(function(){
        showBorder($this, preview, info);
      }).blur(function(){
        hideBorder(preview, info);
      });
    });
  };
})(jQuery);
