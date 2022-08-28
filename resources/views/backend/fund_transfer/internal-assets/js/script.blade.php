<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('s-payment.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'date',
            name:'date',
          },
          {
            data:'total',
            name:'total',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
    paymentMethod()
  })
  $('#date,#issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
});  

window.formRequest= function(){
    $('.submit').attr('disabled',true);
    $('input,select').removeClass('is-invalid');
    let ammount=$('#ammount').val();
    let from_method=$('#from_method').val();
    let to_method=$('#to_method').val();
    let date=$('#date').val();
    let from_bank=$('#from_bank').val();
    let to_bank=$('#to_bank').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('ammount',ammount);
    formData.append('from_method',from_method);
    formData.append('to_method',to_method);
    formData.append('date',date);
    formData.append('from_bank',from_bank);
    formData.append('to_bank',to_bank);
    $('#exampleModalLabel').text('Fund Transfer');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('fund_transfer.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                remove();
                $('#modal').modal('hide');
                $('.submit').attr('disabled',false);
            }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
              $('.submit').attr('disabled',false);
            }
        })
    }else{
      axios.post("{{URL::to('admin/fund_transfer/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              remove();
              $('.submit').attr('disabled',false);
          }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid')
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
              $('.submit').attr('disabled',false);
           }
        })
    }
}
$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Receive');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Receive');
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

function initSelect2(){
$(".ledger").select2({
  theme:'bootstrap4',
  placeholder:'Ledger',
  allowClear:true,
  ajax:{
    url:"{{URL::to('/admin/accounts/get-account-ledger')}}",
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

}
var unique_number=0;
function addItem(){
  let html="<tr>";
      html+="<td><select class='form-control ledger' name='ledger[]'></select></td>";
      html+="<td><select class='form-control subledger' name='subledger[]' id='subledger"+unique_number+"'></select></td>";
      html+="<td><input class='form-control debit' name='ammount[]' placeholder='0.00'></td>";
      html+="<td><input class='form-control comment' name='comment[]' placeholder='write comment'></td>";
      html+="<td><button class='btn btn-danger btn-sm remove'>X</button></td></tr>";
      $('#payment-body').append(html);
      initSelect2();
    
    $("#subledger"+unique_number).select2({
    theme:'bootstrap4',
    placeholder:'Sub Ledger',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/accounts/get-sub-ledger')}}",
      type:'post',
      dataType:'json',
      delay:20,
      data:function(params){
        ledger_id=$(this).parent().prev().children().val();
        return {
          searchTerm:params.term,
          ledger_id:ledger_id,
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
  unique_number=unique_number+1;
}

$(document).on('click','.remove', function(e){
  e.preventDefault();
 console.log($(this).val());
 $(this).parent().parent().remove();
 calculation();
})
$("#to_bank,#from_bank").select2({
    theme:'bootstrap4',
    placeholder:'Select Bank',
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
  $("#supplier").select2({
    theme:'bootstrap4',
    placeholder:'Supplier',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-supplier')}}",
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
function paymentMethod(){
    let to_method=$('#to_method').val()
    let from_method=$('#from_method').val()
    // console.log(method_type)
    console.log(from_method);
    $('#cheque_no').val('');
    $('#cheque_photo').val('');
    $('#bank').val(null).trigger('change');
    if(to_method!=0){
      $('.to_bank').removeClass('d-none');
    }else{
      $('.to_bank').addClass('d-none');
    }
    if(from_method!=0){
      $('.from_bank').removeClass('d-none');
    }else{
      $('.from_bank').addClass('d-none');
    }
    getBalance();
  }

  function remove(){
      $("input").removeClass('is-invalid').val('');
      $(".invalid-feedback").text('');
      $('#method').val(0).trigger('change')
      $('#payment-body').empty();
      console.log('fired');
      $('input').val('');
      $('#date,#issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        parentEl: ".bd-example-modal-lg .modal-body",
      locale: {
          format: 'DD-MM-YYYY',
      }
    });
  }

  $(document).on('focus keyup focusout',"input[name='ammount[]']",function(){
    calculation();
  })
  function calculation(){
    console.log('xyz')
    ammount=$("input[name='ammount[]']").map(function(){
      return $(this).val();
    }).get();
    totalAmt=ammount.reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
    $('#total').text(totalAmt)
  }

  $(document).on('select2:unselect','.subledger', function (e) {
    console.log($(this));
    $(this).empty();
  });

  function getBalance(type,bank_id=null)
  {

    if(type==0){
        axios.get("{{URL::to('admin/accounts/cash-balance')}}")
        .then((response)=>{
            console.log(response)
            showBalance(response.data)
        })
    }else if(bank_id!=null){
        axios.get("{{URL::to('admin/accounts/bank-balance')}}/"+bank_id)
        .then((response)=>{
            console.log(response)
            showBalance(response.data)

        })
    }
      
  }

  function showBalance(money)
  {
        Swal.fire({
        title: 'Are you sure?',
        text: "Your Account has BDT. "+(money).toFixed(2),
        icon: 'warning',
        showCancelButton: true,
        showConfirmButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
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
  }
</script>