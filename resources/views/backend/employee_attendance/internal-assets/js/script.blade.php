<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('employee.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'adress',
            name:'adress',
          },
          {
            data:'phone',
            name:'phone',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
  })
  
window.formRequest= function(){
    $('input,select').removeClass('is-invalid');
    let name=$('#name').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let email=$('#email').val();
    let nid=$('#nid').val();
    let birth_date=$('#birth_date').val();
    let experience=$('#experience').val();
    let department=$('#department').val();
    let salary=$('#salary').val();
    let image=document.getElementById('file').files;
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('name',name);
    formData.append('adress',adress);
    formData.append('phone',phone);
    formData.append('email',email);
    formData.append('nid',nid);
    formData.append('birth_date',birth_date);
    formData.append('experience',experience);
    formData.append('department',department);
    formData.append('salary',salary);
    if(image[0]!=null){
      formData.append('image',image[0]);
    }
    $('#exampleModalLabel').text('Add New Employee');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('employee.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                clear();
                $('#modal').modal('hide');
            }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }else{
      axios.post("{{URL::to('admin/employee/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
          }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid')
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }
}
function readURL(input) {
  if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function (e) {
        document.getElementById('imagex').setAttribute('src', e.target.result)
      };
      reader.readAsDataURL(input.files[0]);
  }
}
$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Employee');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Employee');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
        if(key=='ammount'){
          $('#opening_balance').val(Math.abs(data.data[key]));
        }
         $('#'+key).val(data.data[key]);
         $('#modal').modal('show');
         $('#id').val(data.data.id);
         if(key=='image'){
          $('#imagex').attr('src',baseURL+'/storage/employee/'+data.data[key]);
        }
      })
    })
});
$(document).delegate(".deleteRow", "click", function(){
    let route=$(this).data('url');
    Swal.fire({
      title: 'Are you sure?',
      text: "You won't be able to revert this!",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
      if (result.value==true) {
        axios.delete(route)
        .then((data)=>{
          if(data.data.message){
            toastr.success(data.data.message);
            datatable.ajax.reload();
          }else if(data.data.warning){
            toastr.error(data.data.warning);
          }
        })
      }
    })
});
$("#employee").select2({
    theme:'bootstrap4',
    placeholder:'Employee',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-employee')}}",
      type:'post',
      dataType:'json',
      delay:20,
      data:function(params){
        return {
          searchTerm:params.term,
          _token:"{{csrf_token()}}",
          }
      },
      processResults:function(response){
        return {
          results:response,
        }
      },
      cache:true,
    }
  });
$('#birth_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
  $('form select').val('').niceSelect('update');
}

function timeCalc(){
  const getSeconds = s => s.split(":").reduce((acc, curr) => acc * 60 + +curr, 0);
  intime=$('#in_time').val();
  launch_out_time=$('#launch_out_time').val();
  launch_in_time=$('#launch_in_time').val();
  out_time=$('#out_time').val();
  intime_second=getSeconds(intime)*60;
  launch_out_time_second=getSeconds(launch_out_time)*60;
  var first_hour_second = Math.abs(intime_second - launch_out_time_second);
  console.log(intime_second);
  var first_hours = Math.floor(first_hour_second / 3600);
  var first_hour_minutes = Math.floor(first_hour_second % 3600 / 60);
  var seconds = first_hour_second % 60;
  $('#total_time').text(first_hours+":"+first_hour_minutes+':'+seconds);
  
}
$(document).on('change','#in_time,#launch_out_time,#launch_out_time,out_time',function(){
  timeCalc();
})
$('#date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });

</script>
