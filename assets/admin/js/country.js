$("#country").change(function () {
    var country_id = $(this).val();
    var _url = $("#_url").val();
    $("#state").find("option").not(":first").remove();
    $.ajax({
        url: _url + "/get-state-dropdown/" + country_id,
        type: "get",
        dataType: "json",
        beforeSend: function () {
            $("#loader").show();
        },
        success: function (response) {
            $("#state").html("");
            var len = 0;
            if (response.data != null) {
                len = response.data.length;
            }

            if (len > 0) {
                var option = "<option value=''>Select State</option>";
                $("#state").append(option);
                for (var i = 0; i < len; i++) {
                    var id = response.data[i].id;
                    var name = response.data[i].name;
                    var option =
                        "<option value='" + id + "'>" + name + "</option>";
                    $("#state").append(option);
                }
            } else {
                var option = "<option value=''>No State Found</option>";
                $("#state").html(option);
            }
        },
        complete: function () {
            $("#loader").fadeOut();
        },
    });
});
$("#state").change(function () {
    var state_id = $(this).val();
    var _url = $("#_url").val();
    $("#city").find("option").not(":first").remove();
    $.ajax({
        url: _url + "/get-city-dropdown/" + state_id,
        type: "get",
        dataType: "json",
        beforeSend: function () {
            $("#loader").show();
        },
        success: function (response) {
            $("#city").html("");
            var len = 0;
            if (response.data != null) {
                len = response.data.length;
            }
            if (len > 0) {
                var option = "<option value=''>Select City</option>";
                $("#city").append(option);
                for (var i = 0; i < len; i++) {
                    var id = response.data[i].id;
                    var name = response.data[i].name;
                    var option =
                        "<option value='" + id + "'>" + name + "</option>";
                    $("#city").append(option);
                }
            } else {
                var option = "<option value=''>No City Found</option>";
                $("#city").html(option);
            }
        },
        complete: function () {
            $("#loader").fadeOut();
        },
    });
});



