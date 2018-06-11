/*
 * form styling
 */
(function ($) {
  $(document).ready(function() {

    // Add placeholder to signup form
    $('.form-email').attr('placeholder', 'Adresa ta de email');

    $('input.error, select.error').each(function() {
      $(this).parents('.form-item').first().addClass('error');
    });

    $('input[type="submit"]').on('click',function() {
      $(':input[required]:visible').each(function() {
        if(!$(this).val()){
          $(this).parents('.form-item').first().addClass('error');
        }
      });
    });

  });
}(jQuery));

/*
 * Equalise heights
 */
function equalizeHeights(selector) {
  var heights = new Array();


  // Loop to get all element heights
  jQuery(selector).each(function() {

    // Need to let sizes be whatever they want so no overflow on resize
    jQuery(this).css('min-height', '0');
    jQuery(this).css('max-height', 'none');
    jQuery(this).css('height', 'auto');

    // Then add size (no units) to array
    heights.push(jQuery(this).outerHeight());
  });

  // Find max height of all elements
  var max = Math.max.apply( Math, heights );

  // Set all heights to max height
  jQuery(selector).each(function() {
    jQuery(this).css('height', max + 'px');
  });
}

// call equalizeHeights() when using ajax
(function($) {
  Drupal.behaviors.equalizeHeights = {
    attach: function (context, settings) {
      equalizeHeights();
    }
  };
})(jQuery);
