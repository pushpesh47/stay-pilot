$().ready(function () {
    $("#registerForm").validate({
        ignore: [],

        rules: {
            name: {
                required: true,
                minlength: 3
            },

            email: {
                required: true,
                email: true
            },

            mobile: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },

            password: {
                required: true,
                minlength: 8
            },

            password_confirmation: {
                required: true,
                equalTo: '[name="password"]'
            }
        },

        messages: {
            name: {
                required: "Please enter full name",
                minlength: "Name must be at least 3 characters"
            },

            email: {
                required: "Please enter email",
                email: "Please enter a valid email"
            },

            mobile: {
                required: "Please enter mobile number",
                digits: "Only numbers allowed",
                minlength: "Mobile number must be 10 digits",
                maxlength: "Mobile number must be 10 digits"
            },

            password: {
                required: "Please enter password",
                minlength: "Password must be at least 8 characters"
            },

            password_confirmation: {
                required: "Please confirm password",
                equalTo: "Passwords do not match"
            }
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
            let isValid = true;

            $(".guest-error").remove();

            if ($('input[name="property_id"]').val() !== '') {

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

            let formData = new FormData(form);

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
                        showToast(response.message, "success");
                        let stepCompleted = response.step_completed;

                        if (response.redirect) {
                            window.location.href = response.redirect_url;
                        }

                        if (stepCompleted == 1) {
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
        }
    });

});


function printErrorMsg(msg) {
    $(".error").html("");
    $.each(msg, function (key, value) {
        key = key.replace(/\./g, "_");
        $("." + key + "_err").html(value[0]).show();
    });
}