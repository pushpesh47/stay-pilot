$().ready(function () {
    $("#amenityForm").validate({
        rules: {
            name: {
                required: true,
            },
            icon: {
                required: true,
            },
            amenity_type: {
                required: true,
            },
        },
        messages: {
            name: {
                required: "Please enter name",
            },
            icon: {
                required: "Please enter svg icon",
            },
            amenity_type: {
                required: "Please select amenity Type",
            },
        },
        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".form-group").append(error);
        },
        highlight: function (element) {
            $(element).addClass("is-invalid");
        },
        unhighlight: function (element) {
            $(element).removeClass("is-invalid");
        },
        submitHandler: function (form) {
            var formData = new FormData(form);
            const actionUrl = $(form).attr("action");
            $(".error").html("");

            $.ajax({
                type: "POST",
                url: actionUrl,
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $("#loader").show();
                },
                success: function (response) {
                    if (response.success) {
                        $("#amenityTable").DataTable().ajax.reload(null, false);
                        showToast(response.message, "success");
                        if (response.type == "add") {
                            $("#amenityForm")[0].reset();
                            $("#amenityModal #AmenityType").val("").trigger("change");
                            $(".close").click();
                        } else {
                            $("#amenityModal #modalTitle").html("Add Amenity");
                            $("#editId").val('');
                            $("#amenityModal #amenityForm")[0].reset();
                            $("#amenityModal #AmenityType").val("").trigger("change");
                            $(".close").click();
                        }
                    } else if (response.error) {
                        showToast(response.error, "error");
                    }
                },
                error: function (xhr) {
                    try {
                        const errors = xhr.responseJSON.errors;
                        printErrorMsg(errors);
                    } catch (e) {
                        showToast(
                            "Something went wrong. Please try again.",
                            "error"
                        );
                    }
                },
                complete: function () {
                    $("#loader").fadeOut();
                },
            });
        },
    });
});

function printErrorMsg(msg) {
    $.each(msg, function (key, value) {
        $("." + key + "_err")
            .text(value)
            .show();
    });
}

$(document).ready(function () {
    $(".autocompleteoff").attr("autocomplete", "off");
    setTimeout('$(".autocompleteoff").val("");', 500);
});

$(".custom-close").on("click", function () {
    var $modal = $("#amenityModal");

    var form = $modal.find("form")[0];
    if (form) form.reset();

    $modal.find(".is-invalid").removeClass("is-invalid");
    $modal.find(".invalid-feedback").remove();

    $modal.find(".custom-file-label").text("Upload File");
    $("#showImg").empty();
    $(".editmodal").show();
});

$(document).on("click", ".edit-amenity", function () {
    let encryptedId = $(this).data("id");
    $(".editmodal").hide();

    let baseUrl = $('meta[name="base-url"]').attr("content");
    $.ajax({
        url: baseUrl + "/admin/amenities/" + encryptedId + "/edit",
        type: "GET",
        beforeSend: function () {
            $("#loader").show();
        },
        success: function (res) {

            if (res.status) {
                // Update modal title and fields
                $("#amenityModal #modalTitle").html("Update amenity");
                $("#amenityModal #editId").val(res.doc.id);
                $("#amenityModal #Name").val(res.doc.name);
                $("#amenityModal #Icon").val(res.doc.icon);
                $("#amenityModal #AmenityType").val(res.doc.amenity_type).trigger('change');;
                // Display amenity image if exists

            } else {
                showToast(res.message || "Something went wrong.", "error");
            }
        },
        error: function (xhr) {
            if (xhr.status === 403) {
                alert("Permission denied.");
            } else {
                alert("Failed to load docs details.");
            }
        },
        complete: function () {
            $("#loader").fadeOut();
        },
    });
});


