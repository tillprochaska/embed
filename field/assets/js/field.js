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
          oembedPreviewLoad($this, $preview, $label);
        }, 1000);
      });

      $this.on('blur', function() {
        window.clearTimeout(timer);
        oembedPreviewLoad($this, $preview, $label);
      });

      $icon.on('click', function() {
        window.clearTimeout(timer);
        oembedPreviewLoad($this, $preview, $label);
      });

      oembedPreviewLoad($this, $preview, $label);

    });
  };
})(jQuery);


function oembedPreviewLoad($this, $preview, $label) {
  var url = $.trim($this.val());

  if(url === '') {
    $preview.css('opacity', '0').html('');
    $label.show();

  } else if($this.is(':valid')) {
    $preview.css('opacity', '0');
    $label.show();

    $.ajax({
      url:     $this.data('ajax'),
      type:    'POST',
      data:    { url: url },
      success: function(data) {
        $label.hide();
        $preview.html(data[0]).css('opacity', '1').find('.kirby-plugin-oembed__thumb').click(oembedLoad);
      },
    });
  }
}
