<div class="modal fade" id="addUserModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">Add New {{ $create_title }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.user.store') }}" id="addUserForm" autocomplete="off"
        enctype="multipart/form-data">
        <div class="modal-body">
          <div class="row">
            
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Name</label><span class="text-danger">*</span>
                <input type="text" class="form-control" name="name" placeholder="Name" value="{{{ old('name') }}}"
                  autocomplete="off" required>
                <span class="name_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Email Id</label><span class="text-danger">*</span>
                <input type="email" class="form-control" name="email" id="email" placeholder="Email Id"
                  value="{{{ old('email') }}}" autocomplete="off" required>
                <span class="email_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label>User Type</label><span class="text-danger">*</span>
                <select class="form-control select2" name="role_id" id="role_id">
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
                <select class="form-control select2" name="branch_id" id="branch_id">
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
                <input type="text" class="form-control" name="mobile"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '')" inputmode="numeric" minlength="10"
                  maxlength="10" placeholder="Mobile Number" value="{{{ old('mobile') }}}" autocomplete="off">
                <span class="mobile_err text-danger error"></span>
              </div>
            </div>
            <div class="col-lg-6 col-sm-6">
              <div class="form-group">
                <label for="exampleInputEmail1">Password</label><span class="text-danger">*</span>
                <div style="position: relative;">
                  <input type="password" class="form-control" name="password" id="passwordAdd" placeholder="Password"
                    value="" autocomplete="new-password" required>
                  <span onclick="togglePassword('Add')" id="toggleIconAdd" class="eye-icon">👁️</span>
                </div>
                <span class="password_err text-danger error"></span>
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