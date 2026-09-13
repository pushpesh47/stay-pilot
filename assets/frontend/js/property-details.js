$(document).ready(function (){
    checkPropertyAvailability();
    let bookingModal = new bootstrap.Modal(document.getElementById('bookingGuestModal'));

    $("#guestCount").on("change", function () {
        calculatePropertyCharge();
    });

    $('#sameAsAccount').on('change', function(){
        syncPrimaryGuest();
    });

    $("#bookNow").on('click', function(){
        let propertyId = $('#propertyId').val();
        let checkIn = $("#checkIn").val();
        let checkOut = $("#checkOut").val();
        let totalCost = $("#totalCost").html();
        let daysCount = $("#daysCount").val();
        let guestCount = $("#guestCount").val();
        let baseUrl = $('meta[name="base-url"]').attr("content");
        
        if(!checkIn){
            showToast("Please Select Check In Date","warning");
        }
        if(!checkOut){
            showToast("Please Select Check Out Date","warning");
        }

        if(isLoggedIn){
            // show booking modal
            let html = '';
            for(let i = 0; i < guestCount; i++){
                let guestNameId = '';
                let guestPhoneId = '';
                if(i === 0){
                    guestNameId = 'id="guest1Name"';
                    guestPhoneId = 'id="guest1Phone"';
                }
                html += `<h5 class="guest-title mb-3">
                            Guest ${i + 1}
                        </h5>
                        <div class="row mb-3">
                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Guest Name <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" ${guestNameId} name="guests[${i}][name]" class="form-control guest-name">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        Phone <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" ${guestPhoneId} name="guests[${i}][phone]" class="form-control guest-phone">
                                </div>
                            </div>

                            <div class="col-md-4 mb-3">
                                <div class="form-group">
                                    <label class="form-label">
                                        ID Proof (we don't accept PAN) <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" name="guests[${i}][aadhaar][]" class="form-control guest-aadhaar"
                                        accept=".jpg,.jpeg,.png,.webp,.pdf" multiple>
                                </div>
                            </div>

                        </div>
                `;
            }

            $("#guestFieldsContainer").html(html);
            $("#bookingcheckIn").val(checkIn);
            $("#bookingcheckOut").val(checkOut);
            $('#sameAsAccount').prop('checked', false);
            $('#bookinguestCount').val(guestCount);
            let bookingModal = new bootstrap.Modal(
                document.getElementById('bookingGuestModal')
            );

            bookingModal.show();

            return;
        }else{
            //register user and continue booking
            let form = $('<form>', {
                method: 'POST',
                action: baseUrl + "/booking-signup",
            });

            form.append($('<input>', {
                type: 'hidden',
                name: '_token',
                value: $('meta[name="csrf-token"]').attr('content')
            }));
            
            form.append($('<input>', {
                type: 'hidden',
                name: 'property_id',
                value: propertyId
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'check_in',
                value: checkIn
            }));

            form.append($('<input>', {
                type: 'hidden',
                name: 'check_out',
                value: checkOut
            }));
            form.append($('<input>', {
                type: 'hidden',
                name: 'days_count',
                value: daysCount
            }));
            form.append($('<input>', {
                type: 'hidden',
                name: 'total_cost',
                value: totalCost
            }));
            form.append($('<input>', {
                type: 'hidden',
                name: 'guest_count',
                value: guestCount
            }));
            form.appendTo('body').submit();
        }
    });

    $("#bookingFormSubmitBtn").on('click', function(){
        console.log("bookingFormSubmitBtn clicked");

        let propertyId = $('#propertyId').val();
        let checkIn = $("#checkIn").val();
        let checkOut = $("#checkOut").val();
        let guestCount = $("#guestCount").val();

        // bookingForm validation
        let isValid = true;

        $(".guest-error").remove();

        if ($('input[id="bookingPropertyId"]').val() !== '') {

            $('input[name^="guests"]').each(function () {

                let $field = $(this);

                if ($field.attr('type') === 'file') {

                    if (this.files.length === 0) {

                        isValid = false;

                        $field.closest('.form-group')
                            .append('<span class="text-danger guest-error">Please upload any ID proof. (We don\'t accept PAN). </span>');

                        return;
                    }

                    let file = this.files[0];

                    let allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf'];

                    let extension = file.name.split('.').pop().toLowerCase();

                    if (!allowedExtensions.includes(extension)) {

                        isValid = false;

                        $field.closest('.form-group')
                            .append('<span class="text-danger guest-error">Only JPG, JPEG, PNG, WEBP and PDF files are allowed. </span>');
                    }

                    // 2 MB
                    if (file.size > 2097152) {

                        isValid = false;

                        $field.closest('.form-group')
                            .append('<span class="text-danger guest-error">File size must not exceed 2 MB.</span>');
                    }

                } else {

                    if ($field.val().trim() === '') {

                        isValid = false;

                        $field.closest('.form-group')
                            .append('<span class="text-danger guest-error">This field is required</span>');
                    }
                }

            });
        }

        if (!isValid) {
            return false;
        }  

        // if bookingForm validation ok then get booking amount and other information
        let form = document.getElementById("bookingForm");
        let formData = new FormData(form);
        let baseUrl = $('meta[name="base-url"]').attr("content");
        console.log("bookingForm validation ok");
        $.ajax({
            type: "POST",
            url: baseUrl + "/guest-booking-information",
            data: formData,
            cache: false,
            contentType: false,
            processData: false,

            beforeSend: function () {
                $("#loader").show();
            },

            success: function (response) {
                console.log("guestBookingInformation response", response);
                if (response.success) {
                    showToast(response.message, "success");
                    let stepCompleted = response.step_completed;

                    if (response.redirect) {
                        window.location.href = response.redirect_url;
                    }

                    if (stepCompleted == 1) {
                        $('#bookinDaysCount').val(response.days_count);
                        $('#bookintotalCost').val(response.total_cost);
                        console.log("guest-booking-information response", response);
                        let orderId = response.razorpay.order_id;
                        let options = {
                            key: response.razorpay.key,
                            amount: response.razorpay.amount,
                            currency: "INR",
                            name: response.razorpay.name,
                            description: "Property Booking Payment",
                            order_id: orderId,
                            method: {
                                upi: true,
                                card: true,
                                netbanking: true,
                                wallet: false,
                                emi: false,
                                paylater: false
                            },
                            prefill: {
                                name: $('[name="name"]').val(),
                                email: response.razorpay.email,
                                contact: response.razorpay.contact
                            },

                            theme: {
                                color: "#D4AF37"
                            },

                            handler: function (paymentResponse) {
                                let baseUrl = $('meta[name="base-url"]').attr("content");
                                let formData = new FormData(form);
                                formData.append('razorpay_payment_id', paymentResponse.razorpay_payment_id);
                                formData.append('razorpay_order_id', paymentResponse.razorpay_order_id);
                                formData.append('razorpay_signature', paymentResponse.razorpay_signature);
                                $.ajax({
                                    url: baseUrl + "/verify-payment-and-book",
                                    type: "POST",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    cache: false,
                                    success: function(response) {
                                        if(response.success){
                                            if(response.redirect){
                                                window.location.href = response.redirect_url;
                                            }
                                        }

                                    },

                                    error: function(xhr) {
                                        if (xhr.responseJSON?.errors) {
                                            let errors = xhr.responseJSON.errors;
                                            printErrorMsg(errors);
                                            // Show all validation errors in toast
                                            $.each(errors, function (key, value) {
                                                if (Array.isArray(value)) {
                                                    value.forEach(function(message) {
                                                        showToast(message, "error");
                                                    });
                                                } else {
                                                    showToast(value, "error");
                                                }
                                            });

                                        } else if (xhr.responseJSON?.message) {
                                            showToast(xhr.responseJSON.message, "error");
                                        } else {
                                            showToast("Something went wrong. Please try again.", "error");
                                        }
                                    }
                                });
                            }
                        };

                        let rzp = new Razorpay(options);

                        rzp.on('payment.failed', function (response) {
                            // change status to falied 
                            let baseUrl = $('meta[name="base-url"]').attr("content");
                            showToast('Payment failed.', 'error');
                            
                            $.ajax({
                                url: baseUrl + "/update-failed-payment-status",
                                type: "POST",
                                data: {
                                    razorpay_order_id: orderId,
                                    payment_failed: true,
                                    payment_error: response.error,
                                },
                                success: function(response) {}
                            });                                
                        });
                        rzp.open();
                    }
                }
            },
            error: function (xhr) {
                if (xhr.responseJSON?.errors) {
                    let errors = xhr.responseJSON.errors;
                    printErrorMsg(errors);
                    // Show all validation errors in toast
                    $.each(errors, function (key, value) {
                        if (Array.isArray(value)) {
                            value.forEach(function(message) {
                                showToast(message, "error");
                            });
                        } else {
                            showToast(value, "error");
                        }
                    });

                } else if (xhr.responseJSON?.message) {
                    showToast(xhr.responseJSON.message, "error");
                } else {
                    showToast("Something went wrong. Please try again.", "error");
                }
            },

            complete: function () {
                $("#loader").fadeOut();
            }
        });

    });

});