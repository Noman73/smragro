
{{-- calculator modal --}}
<div class="modal fade" id="calculator" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div id="micalc"> </div>
    </div>
  </div>
</div>
<footer class="main-footer">
    <strong>Copyright &copy;  SMRAGRO</strong>
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{asset('storage/adminlte/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{asset('storage/adminlte/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="{{asset('storage/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- ChartJS -->
{{-- <script src="{{asset('storage/adminlte/plugins/chart.js/Chart.min.js')}}"></script> --}}
<!-- Sparkline -->
{{-- <script src="p{{asset('storage/adminlte/lugins/sparklines/sparkline.js')}}"></script> --}}
<!-- JQVMap -->
{{-- <script src="{{asset('storage/adminlte/plugins/jqvmap/jquery.vmap.min.js')}}"></script> --}}
{{-- <script src="{{asset('storage/adminlte/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script> --}}
<!-- jQuery Knob Chart -->
{{-- <script src="{{asset('storage/adminlte/plugins/jquery-knob/jquery.knob.min.js')}}"></script> --}}
<!-- daterangepicker -->
<script src="{{asset('storage/adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('storage/adminlte/plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="{{asset('storage/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script>
<!-- Summernote -->
{{-- <script src="{{asset('storage/adminlte/plugins/summernote/summernote-bs4.min.js')}}"></script> --}}
<!-- overlayScrollbars -->
<script src="{{asset('storage/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>
<!-- AdminLTE App -->
{{-- <script src="{{asset('storage/adminlte/dist/js/adminlte.js')}}"></script> --}}
<script src="https://adminlte.io/themes/v3/dist/js/adminlte.min.js?v=3.2.0"></script>

<script src="{{asset('storage/adminlte/dist/js/SimpleCalculadorajQuery.js')}}"></script>
{{-- <script src="{{asset('storage/adminlte/dist/js/jquery.plugin.js')}}"></script> --}}
{{-- <script src="{{asset('storage/adminlte/dist/js/jquery.calculator.js')}}"></script> --}}

<!-- AdminLTE for demo purposes -->
<script src="{{asset('storage/adminlte/dist/js/demo.js')}}"></script>
<script src="{{asset('storage/adminlte/plugins/select2/js/select2.full.min.js')}}"></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
{{-- <script src="{{asset('storage/adminlte/dist/js/pages/dashboard.js')}}"></script> --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.19/sweetalert2.min.js" integrity="sha512-8EbzTdONoihxrKJqQUk1W6Z++PXPHexYlmSfizYg7eUqz8NgScujWLqqSdni6SRxx8wS4Z9CQu0eakmPLtq0HA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{asset('storage/adminlte/plugins/moment/moment.min.js')}}"></script>
<script src="{{asset('storage/adminlte/plugins/axios/dist/axios.min.js')}}"></script>
<script>
  axios.defaults.baseURL = "{{URL::to('/')}}";
  var baseURL = "{{URL::to('/')}}";
  $(function () {
    var url = window.location;
    // for single sidebar menu
    $('ul.nav-sidebar a').filter(function () {
        return this.href == url;
    }).addClass('active');

    // for sidebar menu and treeview
    $('ul.nav-treeview a').filter(function () {
        return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview")
        .css({'display': 'block'})
        .addClass('menu-open').prev('a')
        .addClass('active');
});
var url = window.location;

// for sidebar menu entirely but not cover treeview
$('ul.sidebar-menu a').filter(function() {
	 return this.href == url;
}).parent().addClass('active');

// for treeview
$('ul.treeview-menu a').filter(function() {
	 return this.href == url;
}).parentsUntil(".sidebar-menu > .treeview-menu").addClass('active');


// calcualtor 
$("#micalc").Calculadora({'EtiquetaBorrar':'Clear'});


// header calculation

function fetch()

{
        from_date=$('#fromDate').val();
        to_date=$('#toDate').val();
        axios.post(baseURL+'/admin/dashboard-data',{from_date:from_date,to_date:to_date})
        .then((res)=>{
            console.log(res.data.current_balance)
            $('#cash_plus_bank').text(parseFloat(res.data.current_balance).toFixed(2));
        })
}


$(document).ready(function(){
  fetch();
})
</script>
@yield('script')
</body>
</html>
