$(function() {
  $("table#comparison").tablesorter({
    sortList: [[0,0]] // sort things alphabetically (at least approximately...)
      // TODO it would be a good idea to use HTML5 Data attributes to put either the key or the predetermined id as a sort key
  });
});

