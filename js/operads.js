function addDimension() {
  expression = $("td.expression", this).data("expression");
  n = this.children.length - 3 + 1;

  if (expression == "")
    $("td.extendable", this).before("<td class='unknown dimension'>?</td>");
  else
    $("td.extendable", this).before("<td class='dimension'>" + computeDimension(expression.toString(), n) + "</td>");
}

$(document).ready(function() {
  $("table#dimensions th.extendable, table#dimensions td.extendable").click(function() {
    n = $("table#dimensions thead tr th").length - 3 + 1;
    // updating the thead
    $("table#dimensions th.extendable").before("<th class='dimension'>" + n + "</th>");
    // updating the tbody
    $("table#dimensions tbody tr").each(addDimension);
  });
});

