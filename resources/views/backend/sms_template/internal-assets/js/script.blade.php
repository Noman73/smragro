<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('sms_template.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'sms',
            name:'sms',
          },
          {
            data:'area',
            name:'area',
          },
          {
            data:'status',
            name:'status',
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
    let sms=$('#sms').val();
    let area=$('#area').val();
    let status=$('#status').val();
    let formData= new FormData();
    formData.append('sms',sms);
    formData.append('area',area);
    formData.append('status',status);
    $('#exampleModalLabel').text('Add New Sms');
    //axios post request
    axios.post("{{route('sms_template.store')}}",formData)
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


function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
}
</script>