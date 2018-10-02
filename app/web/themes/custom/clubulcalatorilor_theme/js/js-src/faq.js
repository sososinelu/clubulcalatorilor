(function($) {

Drupal.behaviors.faqBehaviour = {
  attach: function (context, settings) {

    var openClass = 'node--faq--open';

    $('.node--faq-question').once('node--faq-question').click(function(event) {

      var node = $(this).closest('.node--faq');
      var answer = $(this).siblings('.node--faq-answer');

      if (node.hasClass(openClass)) {
        // Close.
        node.removeClass(openClass);

        answer.velocity(
          "slideUp",
          {
            duration: 400,
            complete: function() {
            }
          }
        );
      } else {
        // Open.
        node.addClass(openClass);

        answer.velocity(
          "slideDown",
          {
            duration: 400,
            complete: function() {
            }
          }
        );
      }
    });

    var queryString = window.location.search.substring(1);
    var faqCount = $('.node--faq').length;

    if (queryString.indexOf('search=') >= 0 && faqCount == 1) {
      var faqNode = $('.node--faq');
      var faqAnswer = $('.node--faq-answer');

      if (!faqNode.hasClass(openClass)) {
        faqNode.addClass(openClass);

        faqAnswer.velocity(
          "slideDown",
          {
            duration: 400,
            complete: function() {
            }
          }
        );
      }
    }
  }
};
})(jQuery);
