@extends('admin.layouts.master')
@section('title', __('Admin | ' . $data['title']))
@section('maincontent')

<section class="content-header">
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs">
                @can('user.show-profile')
              <li class="nav-item">
                <a href="{{ route('user.editProfile') }}" data-toggle="" aria-expanded="false" class="nav-link active">
                  Profile
                </a>
              </li>
               @endif
              @can('change-password.edit')
              <li class="nav-item">
                <a href="{{ route('change-password') }}" data-toggle="" aria-expanded="true" class="nav-link ">
                  Change Password
                </a>
              </li>
               @endif
              
            </ul>
            <a href="{{ url('dashboard') }}" class="btn btn-danger btn-sm ms-auto">
              <i class="fa fa-arrow-left fa-xs"></i>
            </a>
          </div>

          <div class="tab-content">
            <div class="tab-pane show active">
              <div class="col-lg-12">
                <div class="d-flex w-100 h-100 align-items-center">
                  <form method="POST" id="editUserForm" autocomplete="off" enctype="multipart/form-data">
                    @method('PUT')
                    <input type="hidden" name="id" id="userId">
                    <div class="modal-body">
                      <div class="row">                       
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Name</label><span class="text-danger">*</span>
                            <input type="text" class="form-control" name="name" id="userName" placeholder="Name" value="{{{ old('name') }}}" autocomplete="off" required>
                            <span class="name_err text-danger error"></span>
                          </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Email Id</label><span class="text-danger">*</span>
                            <input type="email" class="form-control" name="email" id="userEmail" placeholder="Email Id" value="{{{ old('email') }}}" autocomplete="off" required>
                            <span class="email_err text-danger error"></span>
                          </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Mobile Number</label><span class="required"></span>
                            <input type="text" class="form-control" name="mobile" id="userMobile" maxlength="10" placeholder="Mobile Number" value="{{{ old('mobile') }}}" autocomplete="off">
                            <span class="mobile_err text-danger error"></span>
                          </div>
                        </div>


                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label for="exampleInputEmail1">Address</label><span class="required"></span>
                            <textarea class="form-control" name="address" id="userAddress" placeholder="Address" autocomplete="off">{{{ old('address') }}}</textarea>
                          </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label>Upload ID Proof</label><br />
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input preview-image" data-preview="#editIdProofImg" id="edit_id_proof" name="id_proof" accept=".png, .jpg, .jpeg" style="cursor: pointer;">
                                <label class="custom-file-label" for="edit_id_proof">Upload File</label>
                              </div>
                            </div>
                            <span class="id_proof_err text-danger error"></span>
                            <small class="form-text text-muted">Allowed JPG or PNG. Max size of 5MB</small>
                            <span id="editIdProofImg"></span>
                          </div>
                        </div>
                        <div class="col-lg-6 col-sm-6">
                          <div class="form-group">
                            <label>Upload Profile Image</label><br />
                            <div class="input-group">
                              <div class="custom-file">
                                <input type="file" class="custom-file-input preview-image" data-preview="#editProfileImgPreview" id="edit_profile_image" name="profile_image" accept=".png, .jpg, .jpeg" style="cursor: pointer;">
                                <label class="custom-file-label" for="edit_profile_image">Upload File</label>
                              </div>
                            </div>
                            <span class="profile_image_err text-danger error"></span>
                            <small class="form-text text-muted">Allowed JPG or PNG. Max size of 5MB</small>
                            <span id="editProfileImgPreview"></span>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-12 col-sm-12 text-center">
                      <div class="form-group mt-4">
                        <button type="submit" class="btn btn-success">
                          Update
                        </button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->
</section>
<input type="hidden" id="encryptedId" value="{{ encrypt($user->id) }}">
@endsection
@section('scripts')
<script src="{{ asset('assets/admin/scripts/users.js') }}"></script>

<script>
  $(window).on("load", function() {
    let encryptedId = $("#encryptedId").val();  
    let baseUrl = $('meta[name="base-url"]').attr("content");
    $.ajax({
      url: baseUrl + "/user/" + encryptedId + "/edit",
      type: "GET",
      beforeSend: function() {
        $("#loader").show();
      },
      success: function(res) {
        if (res.status) {
          // Fill modal form with data
          $("#roleId").val(res.user.role_id);
          $("#userId").val(res.user.id);
          $("#userName").val(res.user.name);
          $("#userEmail").val(res.user.email);
          $("#userMobile").val(res.user.mobile);
          $("#userAddress").val(res.user.address);

          if (res.user.id_proof != "") {
            const signUrl = baseUrl + "/storage/" + res.user.id_proof;
            $("#editIdProofImg").html(
              `<img src="${signUrl}" alt="" style="max-width: 100px; height: 60px;">`
            );
          }

          if (res.user.profile_image != "") {
            const signUrl =
              baseUrl + "/storage/" + res.user.profile_image;
            $("#editProfileImgPreview").html(
              `<img src="${signUrl}" alt="" style="max-width: 100px; height: 60px;">`
            );
          }
          $("#editUserModal").modal("show");
        } else {
          showToast(res.message || "Something went wrong.", "error");
        }
      },
      error: function(xhr) {
        if (xhr.status === 403) {
          alert("Permission denied.");
        } else {
          alert("Failed to load user details.");
        }
      },
      complete: function() {
        $("#loader").fadeOut();
      },
    });
  });
</script>
@endsection