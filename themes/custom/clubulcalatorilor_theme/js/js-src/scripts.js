

(function($) {
  $(document).ready(function() {
    var notification = $('.overlay');
    var close_button = $('.overlay-close');

    $('#block-homepagehowitworksblock .panel span').click(function() {
      notification.show();
      //centrePopup();

      $(document.body).addClass('noscroll');
      notification.velocity({opacity: 1});
      notification.scrollTop = 0;
    });

    $(window).resize(function() {
      //centrePopup();
    });

    // $(document).click(function(event) {
    //   if(!$(event.target).closest('.notification-inner').length) {
    //     hidePopup();
    //   }
    // });

    close_button.click(function(event) {
      hidePopup();
      $(document.body).removeClass('noscroll');
    });

    function centrePopup() {
      var popupHeight = notification.height();
      var marginTop = popupHeight / 2;
      notification.css('margin-top', '-' + marginTop + 'px');
    }

    function hidePopup() {
      if(notification.is(":visible")) {
        notification.velocity({opacity: 0}, 400, function(){
          notification.hide();
        });
      }
    }

  });
})(jQuery);
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
