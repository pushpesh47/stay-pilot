let baseUrl = $('meta[name="base-url"]').attr("content");
/**
 * ============================================================
 * Guest Autocomplete
 * ============================================================
 */
function initGuestAutocomplete() {

    // -------------------------
    // Search by Name
    // -------------------------
    $(document).on('keyup', '.guestName', function () {

        let query = $(this).val();
        let parent = $(this).closest('.guest-item');
        let isSelected = parent.data('selected') || false;

        if (isSelected) {
            parent.data('selected', false);

            parent.find('input[name*="[phone]"]').val('');
            parent.find('.aadhaarOld').val('');
            parent.find('.aadhaarPreview').html('');
        }

        if (query.length < 2) {
            parent.find('.nameResult').hide();
            return;
        }

        $.ajax({
            url:  baseUrl + "/admin/search-guest",
            data: {
                search: query
            },
            success: function (data) {

                let html = '';

                if (!data || data.length === 0) {
                    parent.find('.nameResult').hide();
                    return;
                }

                data.forEach(function (item) {

                    let phone = item.phone || '';

                    html += `
                        <a href="#" class="list-group-item selectGuest"
                            data-name="${item.name || ''}"
                            data-phone="${phone}"
                            data-aadhaar="${item.aadhaar || ''}">
                            ${item.name || ''}
                            ${phone ? '<small class="text-muted"> (' + phone + ')</small>' : ''}
                        </a>`;
                });

                parent.find('.nameResult').html(html).show();
            }
        });

    });


    // -------------------------
    // Search by Phone
    // -------------------------
    $(document).on('keyup', '.guestphone', function () {

        let query = $(this).val();
        let parent = $(this).closest('.guest-item');
        let isSelected = parent.data('selected') || false;

        if (isSelected) {
            parent.data('selected', false);

            parent.find('input[name*="[name]"]').val('');
            parent.find('.aadhaarOld').val('');
            parent.find('.aadhaarPreview').html('');
        }

        if (query.length < 2) {
            parent.find('.phoneResult').hide();
            return;
        }


        $.ajax({
            url:  baseUrl + "/admin/search-guest",
            data: {
                search: query
            },
            success: function (data) {

                let html = '';

                if (!data || data.length === 0) {
                    parent.find('.phoneResult').hide();
                    return;
                }

                data.forEach(function (item) {

                    let phone = item.phone || '';

                    if (!phone.length) {
                        return;
                    }

                    html += `
                        <a href="#" class="list-group-item selectGuest"
                            data-name="${item.name || ''}"
                            data-phone="${phone}"
                            data-aadhaar="${item.aadhaar || ''}">
                            ${item.name || ''}
                            <small class="text-muted">(${phone})</small>
                        </a>`;
                });

                parent.find('.phoneResult').html(html).show();
            }
        });

    });


    // -------------------------
    // User manually edits data
    // -------------------------
    $(document).on('input', 'input[name*="[name]"], input[name*="[phone]"]', function () {

        let parent = $(this).closest('.guest-item');

        if (!parent.data('selected')) {
            return;
        }

        let name = parent.find('input[name*="[name]"]').val().trim();
        let phone = parent.find('input[name*="[phone]"]').val().trim();

        if (name === '' || phone === '') {

            parent.find('input[name*="[name]"]').val('');
            parent.find('input[name*="[phone]"]').val('');
            parent.find('.aadhaarOld').val('');
            parent.find('.aadhaarPreview').html('');

            parent.data('selected', false);
        }

    });


    // -------------------------
    // Select Guest
    // -------------------------
    $(document).on('click', '.selectGuest', function (e) {

        e.preventDefault();

        let parent = $(this).closest('.guest-item');

        parent.data('selected', true);

        let name = $(this).data('name') || '';
        let phone = $(this).data('phone') || '';
        let aadhaar = $(this).data('aadhaar') || '';

        parent.find('.guestName').val(name);
        if (phone !== '') {
            parent.find('.guestphone').val(phone);
        }
        if (aadhaar) {

            let previewHtml = '';

            aadhaar.split(',').forEach(function (file) {

                let fileUrl = baseUrl + '/storage/' + file;
                let ext = file.split('.').pop().toLowerCase();

                if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {

                    previewHtml += `
                        <img src="${fileUrl}"
                             width="100"
                             style="margin-right:5px;">`;

                } else if (ext === 'pdf') {

                    previewHtml += `
                        <a href="${fileUrl}"
                           target="_blank"
                           class="btn btn-sm btn-primary mr-1">
                           View PDF
                        </a>`;
                }

            });

            parent.find('.aadhaarPreview').html(previewHtml);
            parent.find('.aadhaarOld').val(aadhaar);

        } else {

            parent.find('.aadhaarPreview').html('');
            parent.find('.aadhaarOld').val('');

        }

        parent.find('.list-group').hide();

    });


    // -------------------------
    // Hide dropdown
    // -------------------------
    $(document).on('click', function () {

        $('.nameResult, .phoneResult').hide();

    });

}

function initPriceCalculation(){
    $(document).on('input change', '[name="per_day_price"], [name="booking_days"]', function() {

        let price = parseFloat($('[name="per_day_price"]').val()) || 0;
        let days = parseInt($('[name="booking_days"]').val()) || 0;

        let total = price * days;

        $('[name="total_amount"]').val(total);
    });
}

function initGuestManagement(){
    let maxGuests = 5;

    $(document).on('click', '.addGuest', function() {

        let totalGuests = $('.guest-item').length;

        if (totalGuests < maxGuests) {

            let index = totalGuests;

            let html = `<div class="guest-item border rounded p-3 mb-3">
                <div class="row g-2 align-items-end">

                    <div class="col-md-3 mb-2">
                        <label class="mb-1">Guest Name</label>
                        <div class="input-wrapper">
                            <input type="text" name="guests[` + index + `][name]" class="form-control guestName" autocomplete="off"
                            placeholder="Enter Name">
                            <div  class="list-group nameResult"></div>
                        </div>
                    </div>

                    <div class="col-md-3 mb-2">
                        <label class="mb-1">Phone</label>
                        <div class="input-wrapper">
                            <input type="text" name="guests[` + index + `][phone]" autocomplete="off" class="form-control guestphone" 
                            placeholder="Enter Phone" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric">
                            <div class="list-group phoneResult"></div>
                        </div>
                    </div>

                    <div class="col-md-4 mb-2">
                        <label class="mb-1">Aadhaar</label>
                        <input type="file" name="guests[` + index + `][aadhaar][]" multiple class="form-control" accept=".jpg,.jpeg,.png,.webp,.pdf">
                        <div class="aadhaarPreview mt-2"></div>
                        <input type="hidden" name="guests[` + index + `][aadhaar_old]" class="aadhaarOld">
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm removeGuest">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>

                </div>
            </div>`;

            $('#guestWrapper').append(html);

        } else {
            alert('Maximum 5 guests allowed');
        }
    });

    $(document).on('click', '.removeGuest', function() {
        $(this).closest('.guest-item').remove();
    });
}

function initPaymentToggle(){
    $('#payment_mode').change(function() {
        if ($(this).val() == 'cash') {
            $('#cashBox').show();
        } else {
            $('#cashBox').hide();
        }
    });

    $('#transfer').change(function() {
        if ($(this).val() == 'yes') {
            $('#screenshotBox').show();
        } else {
            $('#screenshotBox').hide();
        }
    });
}

function initPropertySelection(){
    $(document).on('change', '#branchId', function () {

        let branchId = $(this).val();
        let checkIn = $('#checkIn').val();
        let checkOut = $('#checkOut').val();


        if (!checkIn) {
            showToast('Please select Check In date first', 'warning');
            return;
        }

        if (!checkOut) {
            showToast('Please select Check Out date first', 'warning');
            return;
        }

        if (!branchId) {
            showToast('Please select Property', 'warning');
            return;
        }

        $.ajax({
            url: baseUrl + '/admin/branches/get-branch-empty-properties',
            type: 'POST',
            data: {
                branch_id: branchId,
                check_in: checkIn,
                check_out: checkOut,
                booking_id: $('#booking_id').val(),
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {

                let options = '<option value="">Select Property No</option>';

                if (response.success && response.properties.length > 0) {

                    $.each(response.properties, function (index, property) {
                        options += `
                            <option value="${property.id}" data-base_price="${property.base_price}">
                                ${property.property_name} (${property.property_number})
                            </option>
                        `;
                    });

                } else {
                    options += '<option value="">No Properties Found</option>';
                }

                $('#propertyNumber').html(options);
            },
            error: function () {
                $('#propertyNumber').html('<option value="">Select Property</option>');
                showToast('Failed to load properties', 'error');
            }
        });
    });
    
    $(document).on('change', '#propertyNumber', function () {

        let basePrice = parseFloat($(this).find(':selected').data('base_price')) || 0;
        $('[name="per_day_price"]').val(basePrice).trigger('input');
    });
}

function initBookingFormValidation(){
    $("#offlineBookingForm").validate({
        rules: {
            branch_id: { required: true },
            room_no: { required: true },
            total_guests: { required: true },
            check_in: { required: true },
            check_out: { required: true },

            per_day_price: { required: true, number: true },
            booking_day: { required: true, number: true },
            total_amount: { required: true, number: true },
            paid_amount: { required: true, number: true },

            "guests[0][name]": { required: true },
            "guests[0][phone]": { required: true, minlength: 10, maxlength: 10, number: true },
            cash_received_by: {
                required: function () {
                    return $('select[name="payment_mode"]').val() === 'cash';
                }
            },
            owner_payment_screenshot: {
                required: function () {
                    return $('select[name="transferred_to_owner"]').val() === 'yes'
                        && $('#booking_id').val().trim() === '';
                }
            }
        },

        messages: {
            branch_id: "Select Property",
            room_no: "Select Property No",
            total_guests: "Select total guests",
            check_in: "Select check-in date",
            check_out: "Select check-out date",

            per_day_price: "Enter per day price",
            booking_day: "Select booking days",
            total_amount: "Enter total amount",
            paid_amount: "Enter paid amount",

            "guests[0][name]": "Enter guest name",
            "guests[0][phone]": "Enter valid phone number",
            cash_received_by: "Enter who received the cash",
            owner_payment_screenshot: "Please upload payment screenshot"
        },

        errorElement: "span",
        errorPlacement: function (error, element) {
            error.addClass("invalid-feedback");
            element.closest(".col-md-3, .col-lg-3, .col-lg-12").append(error);
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

                        showToast(response.message, "success");

                        $("#offlineBookingForm")[0].reset();
                        // Optional: guests reset
                        window.location.href = baseUrl + '/admin/offlinebookings';


                    } else {                       
                        showToast(response.message, "error");
                    }
                },

                error: function (xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        alert('d')
                        printErrorMsg(xhr.responseJSON.errors);
                    } else {
                        showToast("Something went wrong!", "error");
                    }
                },

                complete: function () {
                    $("#loader").fadeOut();
                }
            });
        }
    });
}



$().ready(function () {

    initGuestAutocomplete();
    initPriceCalculation();
    initGuestManagement();
    initPaymentToggle();
    initPropertySelection();
    initBookingFormValidation();
    
    
    


});

function printErrorMsg(msg) {
    $.each(msg, function (key, value) {
        showToast(value, "error");
        $("." + key + "_err").text(value).show()
    });
}

$(document).ready(function () {
    $(".autocompleteoff").attr("autocomplete", "off");
    setTimeout('$(".autocompleteoff").val("");', 500);
});

$("#addUserModal").on("hidden.bs.modal", function () {
    $(this).find("form")[0].reset(); // Reset the form
    $(this).find(".error").text('');
    $(this).find(".is-invalid").removeClass("is-invalid"); // Remove validation errors
    $(this).find(".invalid-feedback").remove(); // Remove error messages
    $(this).find(".custom-file-label").text("Upload File"); // Reset file label text
});
$("#editUserModal").on("hidden.bs.modal", function () {
    $(this).find(".error").text('');

    $(this).find("form")[0].reset(); // Reset the form
    $(this).find(".is-invalid").removeClass("is-invalid"); // Remove validation errors
    $(this).find(".invalid-feedback").remove(); // Remove error messages
    $(this).find(".custom-file-label").text("Upload File"); // Reset file label text

    const passwordInput = $(this).find("#passwordEdit")[0];

    if (passwordInput) {
        passwordInput.setAttribute("disabled", "disabled");
        passwordInput.removeAttribute("required");
    }
});

$(document).on("click", ".edit-record", function () {
    let encryptedId = $(this).data("id");
    $.ajax({
        url: baseUrl + "/admin/user/" + encryptedId + "/edit",
        type: "GET",
        beforeSend: function () {
            $("#loader").show();
        },
        success: function (res) {
            if (res.status) {
                // Fill modal form with data
                $("#editUserModal #roleId").val(res.user.role_id);
                $("#editUserModal #userId").val(res.user.id);
                $("#editUserModal #userName").val(res.user.name);
                $("#editUserModal #userEmail").val(res.user.email);
                $("#editUserModal #userMobile").val(res.user.mobile);
                $("#editUserModal").modal("show");
            } else {
                showToast(res.message || "Something went wrong.", "error");
            }
        },
        error: function (xhr) {
            if (xhr.status === 403) {
                alert("Permission denied.");
            } else {
                alert("Failed to load user details.");
            }
        },
        complete: function () {
            $("#loader").fadeOut();
        },
    });
});
