function addDimension() {
  expression = $("td.expression", this).data("expression");
  n = this.children.length - 2 + 1;


  if (expression == "")
    $("td.expression", this).before("<td class='unknown dimension'>?</td>");
  else
    $("td.expression", this).before("<td class='dimension'>" + computeDimension(expression.toString(), n) + "</td>");
}

$(document).ready(function() {
  $("table#dimensions th.expression").click(function() {
    n = $("table#dimensions thead tr th").length - 2 + 1;
    $("table#dimensions th.expression").before("<th class='dimension'>" + n + "</th>");
    $("table#dimensions tbody tr").each(addDimension);
  });
});

