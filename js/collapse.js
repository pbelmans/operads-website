$(document).ready(function() {
  // collapse all operads
  $("dl.operad dt:nth-child(n+5), dl.operad dd:nth-child(n+5)").hide();
  
  // clicking the operad will toggle its information
  $("dl.operad").click(function(e) {
    // if the click was on the left part of the DL we don't trigger
    if (e.pageX > $(this).offset().left + 0.9 * $(this).width()) {
      $("dt:nth-child(n+5), dd:nth-child(n+5)", this).toggle();
      toggleText(this);
    }
  });

  function indicatorHandler(e) {
    dl = $(e.delegateTarget);

    if (e.pageX > dl.offset().left + 0.9 * dl.width())
      showIndicator(dl);
    else
      hideIndicator(dl);
  }

  function showIndicator(dl) {
    toggleText(dl);
    $("p.toggle", dl).show();
    dl.css("cursor", "pointer");
  }

  function hideIndicator(dl) {
    $("p.toggle", dl).hide();
    $(dl).css("cursor", "auto");
  }

  // add a visual clue that things will change
  $("dl.operad dd.name").append("<p class='toggle'>");
  $("dl.operad").hover(
    function(e) { 
      $(e.delegateTarget).mousemove(indicatorHandler);
    },
    function(e) {
      $(e.delegateTarget).unbind("mousemove");
      hideIndicator(e.delegateTarget);
    }
  );

  // change the toggle symbol according to the visibility of the operad
  function toggleText(dl) {
    if ($("dt:nth-child(n+5), dd:nth-child(n+5)", dl).is(":hidden"))
      $("p.toggle", dl).html("&raquo;");
    else
      $("p.toggle", dl).html("&laquo;");
  };

});
