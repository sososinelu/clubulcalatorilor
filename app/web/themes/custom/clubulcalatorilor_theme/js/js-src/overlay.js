(function($) {
  $(document).ready(function() {
    var notification = $('.overlay');
    var close_button = $('.overlay-close');

    $('#block-homepagehowitworksblock .panel span').click(function(event) {
      alert('HELLO');
      notification.show();
      centrePopup();
      notification.velocity({opacity: 1});
    });

    $(window).resize(function() {
      centrePopup();
    });

    $(document).click(function(event) {
      if(!$(event.target).closest('.notification-inner').length) {
        hidePopup();
      }
    });

    close_button.click(function(event) {
      hidePopup();
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
          setCookie('notification_dismissed', 1, 1);
        });
      }
    }

  });
})(jQuery);
