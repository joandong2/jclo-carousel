jQuery(function ($) {
  $("body").on("click", ".wc_multi_upload_image_button", function (e) {
    e.preventDefault();

    var button = $(this),
      // wp.media object
      jclo_uploader = wp
        .media({ multiple: true })
        .on("select", function () {
          var attech_ids = "";
          attachments;
          // return attachments
          var attachments = jclo_uploader.state().get("selection"),
            attachment_ids = new Array(), // ids of the selected images
            i = 0;
          attachments.each(function (attachment) {
            //console.log("attachment", attachment);
            attachment_ids[i] = attachment["id"];
            attech_ids += "," + attachment["id"]; // meta values
            if (attachment.attributes.type == "image") {
              $(button)
                .siblings("ul")
                .append(
                  '<li data-attechment-id="' +
                    attachment["id"] +
                    '"><a href="' +
                    attachment.attributes.url +
                    '" target="_blank"><img class="true_pre_image" src="' +
                    attachment.attributes.url +
                    '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>'
                );
            } else {
              $(button)
                .siblings("ul")
                .append(
                  '<li data-attechment-id="' +
                    attachment["id"] +
                    '"><a href="' +
                    attachment.attributes.url +
                    '" target="_blank"><img class="true_pre_image" src="' +
                    attachment.attributes.icon +
                    '" /></a><i class=" dashicons dashicons-no delete-img"></i></li>'
                );
            }
            i++;
          });

          var ids = $(button).siblings(".attechments-ids").attr("value");
          if (ids) {
            var ids = ids + attech_ids;
            $(button).siblings(".attechments-ids").attr("value", ids);
          } else {
            $(button)
              .siblings(".attechments-ids")
              .attr("value", attachment_ids);
          }
          $(button).siblings(".wc_multi_remove_image_button").show();
        })
        .open();
  });

  $("body").on("click", ".wc_multi_remove_image_button", function () {
    $(this).hide().prev().val("").prev().addClass("button").html("Add Media");
    $(this).parent().find("ul").empty();
    return false;
  });

  $("body").on("click", ".jclo-images ul li i.delete-img", function () {
    var ids = [];
    var this_c = $(this);
    $(this).parent().remove();
    $(".jclo-images ul li").each(function () {
      ids.push($(this).attr("data-attechment-id"));
    });
    $(".jclo-images").find('input[type="hidden"]').attr("value", ids);
  });
});
