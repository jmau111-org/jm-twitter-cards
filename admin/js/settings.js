(function ($) {
  "use strict";

  /**
   * tabs
   ******************************************************************************************************************/
  $("#tabs div").hide();
  $("#tabs div:first").show();
  $("#tabs a:first").addClass("active");

  $("#tabs a").click(function () {
    $("#tabs a").removeClass("active");
    $(this).parent().addClass("active");
    var currentTab = $(this).attr("href");
    $("#tabs div").hide();
    $(currentTab).show();
    return false;
  });

  /**
   * Uploads
   ******************************************************************************************************************/
  $(".wpsa-browse").on("click", function (event) {
    event.preventDefault();

    var self = $(this);

    // Create the media frame.
    var file_frame = (wp.media.frames.file_frame = wp.media({
      title: self.data("uploader_title"),
      button: {
        text: self.data("uploader_button_text"),
      },
      multiple: false,
    }));

    file_frame.on("select", function () {
      var attachment = file_frame.state().get("selection").first().toJSON();
      self.prev(".wpsa-url").val(attachment.url).change();
    });

    // Finally, open the modal
    file_frame.open();
  });

  /**
   * Hide fields specific such as app fields
   ******************************************************************************************************************/
  function toggleAppFields(value) {
    var target = $(
      ".twitteriPhoneName,.twitteriPhoneUrl,.twitteriPhoneId," +
        ".twitteriPadName,.twitteriPadUrl,.twitteriPadId," +
        ".twitterGooglePlayName,.twitterGooglePlayUrl,.twitterGooglePlayId,.twitterAppCountry"
    );
    target.hide();

    if ("app" === value) {
      target.fadeIn();
    } else {
      target.fadeOut();
    }
  }

  $(".twitterCardType select")
    .on("change", function (event) {
      event.preventDefault();
      toggleAppFields(event.target.value);
    })
    .change();
})(jQuery);
