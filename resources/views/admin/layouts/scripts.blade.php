<input type="hidden" id="_url" value="{{url('/')}}">
<script src="{{ asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<script src="{{ asset('assets/admin/js/adminlte.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/chartjs/chart.umd.min.js') }}"></script>
<script src="{{ asset('assets/admin/plugins/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('assets/admin/js/select2.min.js')}}" type="text/javascript"></script>
<script type="text/javascript" src="{{ asset('assets/admin/js/jquery.validate.js')}}"></script>
<script src="{{asset('assets/admin/js/toastify.min.js')}}"></script>
<script src="{{asset('assets/admin/scripts/helper.js')}}" type="text/javascript"></script>
<script src="{{ asset('assets/admin/js/country.js') }}"></script>
<script src="{{ asset('assets/admin/js/bootstrap-toggle.min.js')}}"></script>
<script src="{{ asset('assets/admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>
<script src="https://unpkg.com/@panzoom/panzoom/dist/panzoom.min.js"></script>
<script src="{{ asset('assets/admin/ckeditor/ckeditor.js')}}"></script>


<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<!-- DataTables Responsive -->
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
<!-- DataTables Buttons -->
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>
<!-- JSZip and pdfmake for Excel/PDF buttons -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>






</script>
<script type="text/javascript">
  $(function() {
    $.ajaxSetup({
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      // isLocal: false
    });
  });
  $(function () {
      $('[data-toggle="tooltip"]').tooltip();
  });
  $(document).ready(function() {

    $('.select2').select2({
      // placeholder: 'Select a company',
      width: '100%',
      placeholder: "Select an option",
      allowClear: true
    });

  });

  document.addEventListener('DOMContentLoaded', function() {
    @if(session('success'))
    Toastify({
      text: "{{ session('success') }}",
      duration: 3000,
      gravity: "top",
      position: 'right',
      backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
    }).showToast();
    @endif

    @if(session('error'))
    Toastify({
      text: "{{ session('error') }}",
      duration: 3000,
      gravity: "top",
      position: 'right',
      backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
    }).showToast();
    @endif
  });

  @if($errors->any())

  @foreach($errors->all() as $error)
  Toastify({
    text: "{{ $error }}",
    duration: 4000,
    close: true,
    gravity: "top", // top or bottom
    position: "right", // left, center or right
    backgroundColor: "#f44336",
  }).showToast();
  @endforeach

  @endif

  function myFunction() {
    setTimeout(showPage, 50);
  }

  function showPage() {
    document.getElementById("loader").style.display = "none";
  }

  $(function() {
    bsCustomFileInput.init();
  });
</script>
@yield('script')