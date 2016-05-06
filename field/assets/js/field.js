(function($) {
  $.fn.oembedfield = function() {
    return this.each(function() {

      var $this = $(this);

      if($this.data('oembedfield')) {
        return;
      } else {
        $this.data('oembedfield', true);
      }

      var $icon = $this.next('.field-icon');

      $icon.css({
        'cursor': 'pointer',
        'pointer-events': 'auto'
      });


      var container = $this.parent().next();
      var $preview  = container.find('.field-oembed-preview__bucket');
      var $label    = container.find('.field-oembed-preview__label');


      var timer;
      $this.bind('input', function() {
        window.clearTimeout(timer);
        timer = window.setTimeout(function(){
          oembedPreviewLoad($this, $preview, $label, true);
        }, 1000);
      });

      $this.on('blur', function() {
        window.clearTimeout(timer);
        oembedPreviewLoad($this, $preview, $label, true);
      });

      $icon.on('click', function() {
        window.clearTimeout(timer);
        oembedPreviewLoad($this, $preview, $label, true);
      });

      oembedPreviewLoad($this, $preview, $label, false);

    });
  };
})(jQuery);


function oembedPreviewLoad($this, $preview, $label, triggered) {
  var url = $.trim($this.val());

  if(url === '') {
    $label.show();
    $preview.css('opacity', '0');
    $preview.html('');

  } else if($this.is(':valid')) {
    if(triggered === true) {
      $label.show();
      $preview.css('opacity', '0');
    }

    $.ajax({
      url:  $this.data('ajax'),
      type: 'POST',
      data: {
        url: url
      },
      success: function(data) {
        $label.hide();
        $preview.html(data[0]);
        $preview.css('opacity', '1');
        $preview.find('.kirby-plugin-oembed__thumb').click(oembedLoad);
      },
    });
  }
}
