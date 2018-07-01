

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

    //equalizeHeights($('#block-homepagepremiumblock  .panel'));

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

// scroll to the top
(function($) {
  // When the user scrolls down 20px from the top of the document, show the button
  window.onscroll = function() {scrollFunction()};

  function scrollFunction() {
    if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
      $(".top").show();
    } else {
      $(".top").hide();
    }
  }


// Select all links with hashes
$('a[href*="#"]')
  .click(function(event) {

    // On-page links
      // Figure out element to scroll to
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
      // Does a scroll target exist?
      if (target.length) {
        // Only prevent default if animation is actually gonna happen
        event.preventDefault();
        $('html, body').animate({
          scrollTop: target.offset().top
        }, 800, function() {

          // Callback after animation
          // Must change focus!
          var $target = $(target);
          $target.focus();
          if ($target.is(":focus")) { // Checking if the target was focused
            return false;
          } else {
            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
            $target.focus(); // Set focus again
          };
        });
      }
  })
})(jQuery);
