$(document).ready(function() {
  $("dd.extendable ol").click(function() {
    expression = $($(this).context.parentNode.children[1]).data("expression");
    n = $(this).context.children.length + 1;

    $(this).append("<li>" + computeDimension(expression, n));

    // stop the event from propagating (i.e. in the collapsible view)
    event.stopPropagation();
  });
});
