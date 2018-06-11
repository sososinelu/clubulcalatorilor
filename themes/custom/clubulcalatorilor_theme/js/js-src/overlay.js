(function($) {
  $(document).ready(function() {
    var notification = $('.overlay');
    var close_button = $('.overlay-close');

    var dismissed_cookie = getCookie('notification_dismissed');

    if (!dismissed_cookie) {
      notification.show();
      centrePopup();
      notification.velocity({opacity: 1});
    }

    function setCookie(cname, cvalue, exdays) {
      var d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      var expires = "expires="+ d.toUTCString();
      document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    }

    function getCookie(cname) {
      var name = cname + "=";
      var decodedCookie = decodeURIComponent(document.cookie);
      var ca = decodedCookie.split(';');
      for(var i = 0; i <ca.length; i++) {
          var c = ca[i];
          while (c.charAt(0) == ' ') {
              c = c.substring(1);
          }
          if (c.indexOf(name) == 0) {
              return c.substring(name.length, c.length);
          }
      }
      return "";
    }

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

  });
})(jQuery);
