var math = mathjs({number: "bignumber"});

math.import({
  choose: function(n, k) {
    var result = 1;
    for (var i = 1; i <= k; i++)
      result = result * (n - (k - i)) / i;
    return result;
  }
});

function computeDimension(expression, n) {
  // TODO there is a potential issue if the expression contains the letter n in an operator
  return math.eval(expression.replace(/n/g, n)).toFixed();
}

$(document).ready(function() {
  $("dd.extendable ol").click(function() {
    expression = $($(this).context.parentNode.children[1]).data("expression");
    n = $(this).context.children.length + 1;

    $(this).append("<li>" + computeDimension(expression, n));

    // stop the event from propagating (i.e. in the collapsible view)
    event.stopPropagation();
  });
});

