<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('customer.index')}}"
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
            data:'bank_name',
            name:'bank_name',
          },
          {
            data:'bank_account_no',
            name:'bank_account_no',
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
    let company_name=$('#company_name').val();
    let name=$('#name').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let phone2=$('#phone2').val();
    let bank_name=$('#bank_name').val();
    let bank_account_no=$('#bank_account_no').val();
    let email=$('#email').val();
    let nid=$('#nid').val();
    let birth_date=$('#birth_date').val();
    let image=document.getElementById('file').files;
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('company_name',company_name);
    formData.append('name',name);
    formData.append('adress',adress);
    formData.append('phone',phone);
    formData.append('phone2',phone2);
    formData.append('bank_name',bank_name);
    formData.append('bank_account_no',bank_account_no);
    formData.append('email',email);
    formData.append('nid',nid);
    formData.append('birth_date',birth_date);
    if(image[0]!=null){
      formData.append('image',image[0]);
    }
    $('#exampleModalLabel').text('Add New Customer');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('customer.store')}}",formData)
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
      axios.post("{{URL::to('admin/customer/')}}/"+id,formData)
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
    $('#exampleModalLabel').text('Add New Customer');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Customer');
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
          $('#imagex').attr('src',baseURL+'/storage/customer/'+data.data[key]);
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
</script>
