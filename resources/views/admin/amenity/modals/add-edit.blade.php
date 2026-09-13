<div class="modal fade" id="amenityModal" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="modalTitle">Add {{ $create_title }}</h4>
        <button type="button" class="close custom-close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form method="POST" action="{{ route('admin.amenities.store') }}" id="amenityForm" autocomplete="off"
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
                       name="name" id="Name" placeholder="Name"
                      value="" autocomplete="off" required>
                    <span class="name_err text-danger error"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="">SVG Icon<span class="text-danger">*</span></label>
                    <textArea class="form-control"
                      name="icon" id="Icon" placeholder="SVG"
                      autocomplete="off" required></textArea>
                    <span class="icon_err text-danger error"></span>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-group">
                    <label for="">Amenity Type<span class="text-danger">*</span></label>
                    <select class="form-control select2" name="amenity_type" id="AmenityType">
                      <option value="">Select Option</option>
                      <option value="property">Property</option>
                      <option value="branch">Branch</option>
                    </select>
                    <span class="amenity_type_err text-danger error"></span>
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