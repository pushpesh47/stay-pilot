<div class="modal fade" id="editUserModal" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Edit User Details</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" id="editUserForm" autocomplete="off" enctype="multipart/form-data">
        @method('PUT')
        <input type="hidden" name="id" id="userId">
        <div class="modal-body">
          <div class="row">
            
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" id="userName" placeholder="Name"
                  value="{{{ old('name') }}}" autocomplete="off" required>
                <span class="name_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Email Id</label><span class="text-danger">*</span>
                <input type="email" class="form-control" name="email" id="userEmail" placeholder="Email Id"
                  value="{{{ old('email') }}}" autocomplete="off" required>
                <span class="email_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label>User Type</label><span class="text-danger">*</span>
                <select class="form-control select2" name="role_id" id="roleId">
                  <option value="">Select User Type</option>
                  @foreach($roles as $role)
                  <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                  @endforeach
                </select>
                <span class="role_id_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label>Branch</label><span class="text-danger">*</span>
                <select class="form-control select2" name="branch_id" id="branchId">
                  <option value="">Select branch</option>
                  @foreach($branches as $branch)
                  <option value="{{ $branch->id }}">{{ ucfirst($branch->name) }},{{ ucfirst($branch->location) }},{{ ucfirst($branch->city->name) }},{{ ucfirst($branch->city->state) }} - {{ ucfirst($branch->pincode) }}</option>
                  @endforeach
                </select>
                <span class="branch_id_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Mobile Number</label><span class="required"></span>
                <input type="text" class="form-control" name="mobile" id="userMobile" maxlength="10"
                  placeholder="Mobile Number" value="{{{ old('mobile') }}}" autocomplete="off">
                <span class="mobile_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="enablePasswordEdit">
                  <input type="checkbox" id="enablePasswordEdit" onchange="toggleShowPassword('Edit')"> Set Password
                </label>
                <label for="passwordEdit">Password <span class="required">*</span></label>
                <div style="position: relative;">
                  <input type="password" class="form-control pr-5" id="passwordEdit" name="password" maxlength="15"
                    placeholder="Password" disabled>
                  <span onclick="togglePassword('Edit')" id="toggleIconEdit" class="eye-icon">👁️</span>
                </div>
              </div>
            </div>            
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
      </form>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<!-- /.modal -->