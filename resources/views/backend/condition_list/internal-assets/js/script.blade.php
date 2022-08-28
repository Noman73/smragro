<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        ajax:{
          url:"{{URL::to('admin/condition-list')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'id',
            name:'id',
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'mobile',
            name:'mobile',
          },
          {
            data:'total_payable',
            name:'total_payable',
          },
          {
            data:'pay',
            name:'pay',
          },
          {
            data:'sleep_no',
            name:'sleep_no',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
  })
    

window.formRequest= function(){
    let ammount=$('#ammount').val();
    let method=$('#method').val();
    let date=$('#date').val();
    let bank=$('#bank').val();
    let cheque_no=$('#cheque_no').val();
    let cheque_photo=$('#cheque_photo').val();
    let issue_date=$('#issue_date').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('ammount',ammount);
    formData.append('method',method);
    formData.append('date',date);
    formData.append('bank',bank);
    formData.append('cheque_no',cheque_no);
    formData.append('issue_date',issue_date);
    formData.append('cheque_photo',cheque_photo);
    formData.append('invoice_id',id);
    $('#exampleModalLabel').text('Add New Payment');
    if(id!=''){
      // formData.append('_method','PUT');
    }
    //axios post request
  
         axios.post("{{route('condition_receive.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                remove();
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
    $('#exampleModalLabel').text('Add New Bank Account');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Bank Account');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
        if(key=='open_ammount'){
          $('#opening_balance').val(Math.abs(data.data[key]));
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
  $('form select').val('').niceSelect('update');
}
$("#bank").select2({
    theme:'bootstrap4',
    placeholder:'Payment Method',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-payment-method')}}",
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

  $('#date,#issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
});  
  function paymentMethod(){
    let method_type=$('#method').val()
    console.log(method_type)
    $('#cheque_no').val('');
    $('#cheque_photo').val('');
    $('#bank').val(null).trigger('change');
    if(method_type==1){
      $('.bank').removeClass('d-none');
    }else{
      $('.bank').addClass('d-none');
    }
  }

  $(document).ready(function(){
  paymentMethod();
})
 
 function setInvoiceId(id)
 {
    $('#id').val(id);
 }

 function sleepRequest(){
    let sleep_no=$('#sleep_no').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('sleep_no',sleep_no);
    formData.append('invoice_id',id);
    $('#exampleModalLabel').text('Add New Sleep');
    if(id!=''){
      // formData.append('_method','PUT');
    }
        //axios post request
         axios.post("{{route('sleep.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                remove();
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
</script>
