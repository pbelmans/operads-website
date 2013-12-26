var math = mathjs({number: "bignumber"});


math.import({
  choose: function(n, k) {
    return math.divide(math.factorial(math.bignumber(n)), math.multiply(math.factorial(math.bignumber(k)), math.factorial(math.bignumber(n-k))));
  },
  schroeder: function(n) {
    n = parseInt(n);

    var result = math.bignumber("0");
    for (var k = 0; k <= n-1; k++)
      result = math.add(result, math.multiply(math.choose(2*n-k, n), math.choose(n-1, k)));
    return math.divide(result, n+1);
    // TODO remark that in operad-data/operads/2as.json there might be an error in both the generating series (missing factor in the denominator) and the general term (2C_{n-1}?)
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

