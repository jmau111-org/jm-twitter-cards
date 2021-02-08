(function ($) {
  "use strict";
  $(".tc .icon").css("margin-right", "7px");
  var target = $("#github-repositories");
  target.html("<span>...</span>");
  var repos = JSON.parse(tcGitHub.repositories);
  var list = $("<dl/>");
  target.empty().append(list);
  $(repos).map(function (i, e) {
    if (this.name !== tcGitHub.user.toLowerCase() + ".github.com") {
      list.append(
        '<dt><a href="' +
          (e.homepage ? e.homepage : e.html_url) +
          '">' +
          e.name +
          "</a> <em>" +
          (e.language ? "(" + e.language + ")" : "") +
          "</em></dt>"
      );
      list.append("<dd>" + e.description + "</dd>");
    }
  });
})(jQuery);
