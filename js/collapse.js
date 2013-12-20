$(document).ready(function() {
  // collapse all operads
  $("dl.operad dt:nth-child(n+5), dl.operad dd:nth-child(n+5)").hide();
  // clicking the operad will toggle its information
  $("dl.operad").click(function() {
    $("dt:nth-child(n+5), dd:nth-child(n+5)", this).toggle();
    toggleText(this);
  });

  // add a visual clue that things will change
  $("dl.operad").append("<p class='toggle'>");
  $("dl.operad").hover(
    function() {
      toggleText(this);
      $("p.toggle", this).show();
    },
    function() {
      $("p.toggle", this).hide();
    }
  );

  // change the toggle symbol according to the visibility of the operad
  function toggleText(dl) {
    if ($("dt:nth-child(n+5), dd:nth-child(n+5)", dl).is(":hidden"))
      $("p.toggle", dl).text(">>"); // TODO some nicer symbol
    else
      $("p.toggle", dl).text("<<"); // TODO some nicer symbol
  };

});