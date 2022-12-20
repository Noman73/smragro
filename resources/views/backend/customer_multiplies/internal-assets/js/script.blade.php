<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('customer-multiply.index')}}"
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
            data:'brand',
            name:'brand',
          },
          {
            data:'multiply',
            name:'multiply',
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
    let customer=$('#customer').val();
    let brand=$('#brand').val();
    let multiply=$('#multiply').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('customer',customer);
    formData.append('brand',brand);
    formData.append('multiply',multiply);
    $('#exampleModalLabel').text('Add New Category');
    console.log(id)
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('customer-multiply.store')}}",formData)
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
      axios.post("{{URL::to('admin/customer-multiply/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
              $('#modal').modal('hide');
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
         $('#'+key).val(data.data[key]);
         $('#modal').modal('show');
         $('#id').val(data.data.id);
         if(key=='customer'){
          $('#'+'customer').html("<option value='"+data.data[key].id+"'>"+data.data[key].name+'('+data.data[key].phone+')'+"</option>")
         }
         if(key=='brand'){
          $('#'+'brand').html("<option value='"+data.data[key].id+"'>"+data.data[key].name+"</option>")
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
$("#brand").select2({
    theme:'bootstrap4',
    placeholder:'Brand',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-brand')}}",
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
$("#customer").select2({
    theme:'bootstrap4',
    placeholder:'Customer',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-customer')}}",
      type:'post',
      dataType:'json',
      delay:20,
      data:function(params){
        return {
          searchTerm:params.term,
          market:$('#market').val(),
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
  })

function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
}


</script>
