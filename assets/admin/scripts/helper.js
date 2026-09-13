function showToast(message, type = "success") {
    let background =
        type === "success"
            ? "linear-gradient(to right, #00b09b, #96c93d)" // green for success
            : "linear-gradient(to right, #ff5f6d, #ffc371)"; // red/orange for error

    Toastify({
        text: message,
        duration: 4000,
        gravity: "top",
        position: "right",
        backgroundColor: background,
    }).showToast();
}

function toggleShowPassword(context) {
    const checkbox = document.getElementById("enablePassword" + context);
    const passwordInput = document.getElementById("password" + context);
    if (checkbox.checked) {
        passwordInput.removeAttribute("disabled");
        passwordInput.setAttribute("required", "required");
    } else {
        passwordInput.setAttribute("disabled", "disabled");
        passwordInput.removeAttribute("required");
        passwordInput.value = "";
    }
}

function togglePassword(context) {
    const passwordInput = document.getElementById("password" + context);
    const icon = document.getElementById("toggleIcon" + context);
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        icon.textContent = "🙈";
    } else {
        passwordInput.type = "password";
        icon.textContent = "👁️";
    }
}

document.querySelectorAll(".preview-image").forEach((input) => {
    input.addEventListener("change", function (e) {
        const file = this.files[0];
        const previewSelector = this.dataset.preview;
        const preview = document.querySelector(previewSelector);
        const label = this.nextElementSibling;

        preview.innerHTML = ""; // Clear preview first

        if (file) {
            label.textContent = file.name;

            const allowedTypes = ["image/jpeg", "image/png", "application/pdf"];
            const maxSize = 20 * 1024 * 1024; // 20MB

            if (!allowedTypes.includes(file.type) || file.size > maxSize) {
                preview.innerHTML = `<small class="text-danger">Invalid file or size too large (max 20MB).</small>`;
                return;
            }

            const reader = new FileReader();
            reader.onload = (e) => {
                if (file.type === "application/pdf") {
                    preview.innerHTML = `<a href="${e.target.result}" class="" target="_blank" download="${file.name}">Download PDF</a>`;
                } else {
                    preview.innerHTML = `<img src="${e.target.result}" class="img-thumbnail mt-2" style="max-height: 80px;">`;
                }
            };
            reader.readAsDataURL(file);
        }
    });
});


$(document).on("click", ".toggle-status", function () {
    const el = $(this);
    const userId = el.data("id");
    const currentStatus = el.data("status");
    const url = el.data("url");
    const tableId = el.data("tableid");
    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            id: userId,
            status: currentStatus,
        },
        success: function (response) {
            el.data("status", response.newStatus);
            el.html(
                `<i class="fa fa-fw ${response.icon} ${response.class}"></i>`
            );
            showToast(response.message, "success");
            $("#" + tableId)
                .DataTable()
                .ajax.reload(null, false);
        },
        error: function (xhr) {
            showToast(xhr.responseJSON?.message || "Something went wrong.");
        },
    });
});

$(document).on("click", ".delete-record", function () {
    const el = $(this);
    const url = el.data("url");
    const title = el.data("title");
    const tableId = el.data("tableid");

    if (!confirm("Are you sure you want to delete this " + title + " ?")) {
        return;
    }

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: $('meta[name="csrf-token"]').attr("content"),
            _method: "DELETE",
        },
        success: function (response) {
            showToast(response.message, "success");
            $("#" + tableId).DataTable().ajax.reload(null, false);
        },
        error: function (xhr) {
            showToast( xhr.responseJSON?.message || "Failed to delete.",  "error" );
        },
    });
});









