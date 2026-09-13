<div class="modal fade" id="cityModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modalTitle">Add {{ $create_title }}</h4>
        <button type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.cities.store') }}" id="cityForm" autocomplete="off"
        enctype="multipart/form-data">
        <div class="modal-body">
          <div class="container d-flex justify-content-center">
            <div class="w-100" style="max-width: 400px;">
              <div class="row">
                <input type="hidden" name="id" id="editId" value="">
                <div class="col-12">
                  <div class="form-group">
                    <label for="">Name<span class="text-danger">*</span></label>
                    <input type="text" class="form-control"
                       name="name" id="Name" placeholder="City Name"
                      value="" autocomplete="off" required>
                    <span class="name_err text-danger error"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="">State<span class="text-danger">*</span></label>
                    <input type="text" class="form-control"
                       name="state" id="State" placeholder="State"
                      value="" autocomplete="off" required>
                    <span class="state_err text-danger error"></span>
                  </div>
                </div>
                               
              </div>
            </div>
          </div>
        </div>

        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default custom-close" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary"><i class="fa fa-check-circle"></i> Submit</button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- /.modal -->