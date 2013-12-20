var math = mathjs({number: "bignumber"});

function computeDimension(expression, n) {
  // there is a potential issue if the expression contains the letter n in an operator
  // TODO add both TeX and math.js readable expression in database
  return math.eval(expression.replace("n", n)).toFixed();
}

$(document).ready(function() {
  $("dd.dimensions ol").click(function() {
    // TODO more robust way of selecting this
    // TODO make sure nothing happens when the expression is not available
    // TODO make sure the styling is only applied when there is an expression available
    expression = $(this).context.parentNode.children[1].title;
    n = $(this).context.children.length + 1;

    $(this).append("<li>" + computeDimension(expression, n));
  });
});

