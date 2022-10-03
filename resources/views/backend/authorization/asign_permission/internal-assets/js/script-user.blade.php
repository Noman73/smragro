<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('category.index')}}"
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
            data:'action',
            name:'action',
          }
        ]
    });
  })
    
  $("#user").select2({
    theme:'bootstrap4',
    placeholder:'Select Note',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-user')}}",
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
window.formRequest= function(){
    $('input,select').removeClass('is-invalid');
    let permission=$("input[name='permissions[]']").map(function(){
      return $(this).val();
    }).get();
    let user=$("#user").val();
    if(user==null){
        user='';
    }
    let condition=$("input[name='permissions[]']").map(function(){
      return  ($(this).prop("checked") ? true : false);
    }).get();
    console.log(permission,condition)

    let formData= new FormData();
    formData.append('permission',permission);
    formData.append('user',user);
    formData.append('condition',condition);
    $('#exampleModalLabel').text('Add New Permission');
    //axios post request
    
      axios.post("{{route('asign-permission-user.store')}}",formData)
    .then(function (response){
        console.log(response)
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
}


$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Category');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Category');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
        if(key=='category_id'){
          $('#category').val(data.data[key]).niceSelect('update');
        }
         $('#'+key).val(data.data[key]);
         $('#modal').modal('show');
         $('#id').val(data.data.id);
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
$(document).on('select2:select',"#user", function (e){
  user_id=$(this).val();
  axios.get("{{URL::to('admin/get-model-has-permission')}}/"+user_id)
  .then((res)=>{
    console.log(res.data)
    data=res.data;
    $("input[name='permissions[]']").attr('checked',false)
    data.forEach(function(d){
      x=$('#data'+d.permission_id).attr('checked',true);
    })
  })
})
$(document).ready(function(){

})
function clear(){
  $("input").removeClass('is-invalid');
  $(".invalid-feedback").text('');
}
</script>
