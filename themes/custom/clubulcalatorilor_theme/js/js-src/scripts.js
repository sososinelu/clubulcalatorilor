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

(function($) {
  // Mobile menu toggle
  var menuToggle = $('.menu-toggle');
  var mobileMenu = $('.block-mobile-menu-block');

  menuToggle.click(function() {
    if ($(this).hasClass('is-active')) {
      $(this).removeClass('is-active');
      mobileMenu.hide();
    } else {
      $(this).addClass('is-active');
      mobileMenu.show();
    }
  });

  // Hide main menu column labels on the sitemap
  if ($('.sitemap').length){
    var span_column_text = $("span:contains('Column')");

    span_column_text.addClass('hide');
    span_column_text.closest('ul').addClass('hide-bullet-points');
  }

}(jQuery));

/*
 * form styling
 */
(function ($) {
  $(document).ready(function() {

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

(function ($) {
  $(document).ready(function(){

    var exclude_selects = ['#edit-press-release-category', '#edit-news-category',
    '#edit-resource-category', '#edit-status', '#edit-faq-category', '#edit-sort-bef-combine'];

    var sumo_exclude = exclude_selects.join(', ');

    // Apply SumoSelect to all select elements apart from those listed.
    $('select').not(sumo_exclude).SumoSelect();

  });
}(jQuery));
