$(document).ready(function(){

  $(".notifications.dropdown-menu .notification-actions form").live("submit", function(evt){
    $.ajax({
      type: "POST",
      format: 'json',
      url: $(this).attr('action'),
      data: $(this).serialize(), // serializes the form's elements.
      success: function(data)
      {
          var obj = $.parseJSON(data);
          $("#notification-" + obj.notification).fadeOut().remove();

          $("#num-notifications").text($("#num-notifications").text() - 1)
          if ($("#num-notifications").text() < 1) {
              $(".notification-list").remove();
          }
      }
    });

    return false;
  });
});