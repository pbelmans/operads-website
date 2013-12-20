$(document).ready(function() {
  // collapse all operads
  $("dl.operad dt:nth-child(n+5), dl.operad dd:nth-child(n+5)").hide();
  // clicking the operad will toggle its information
  $("dl.operad").click(function() {
    $("dt:nth-child(n+5), dd:nth-child(n+5)", this).toggle();
  });

  // add a visual clue that things will change
  $("dl.operad").hover(
    function() {
      $("p.toggle", this).remove();
      if ($("dt:nth-child(n+5), dd:nth-child(n+5)", this).is(":hidden"))
        $(this).append("<p class='toggle'>&gt;&gt;"); // TODO some nicer symbol
      else
        $(this).append("<p class='toggle'>&lt;&lt;"); // TODO some nicer symbol
    },
    function() {
      $("p.toggle", this).remove();
    }
  );
});
