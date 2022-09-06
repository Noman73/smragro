<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('c-receive.index')}}"
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
            data:'trx_id',
            name:'trx_id',
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
    let customer=$('#customer').val();
    let ammount=$('#ammount').val();
    let method=$('#method').val();
    let date=$('#date').val();
    let bank=$('#bank').val();
    let cheque_no=$('#cheque_no').val();
    let cheque_photo=$('#cheque_photo').val();
    let issue_date=$('#issue_date').val();
    let note=$('#note').val();
    let v_id_cus=$('#v_id_cus').val();
    let v_id_method=$('#v_id_method').val();
    let id=$('#id').val();
    let formData= new FormData();
   
    formData.append('customer',customer);
    formData.append('ammount',ammount);
    formData.append('method',method);
    formData.append('date',date);
    formData.append('bank',bank);
    formData.append('cheque_no',cheque_no);
    formData.append('issue_date',issue_date);
    formData.append('cheque_photo',cheque_photo);
    formData.append('note',note);
    $('#exampleModalLabel').text('Add New Receive');
    if(id!=''){
      formData.append('_method','PUT');
      formData.append('v_id_cus',v_id_cus);
      formData.append('v_id_method',v_id_method);
    }
    //axios post request
    if (id==''){
         axios.post("{{route('c-receive.store')}}",formData)
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
    }else{
      axios.post("{{URL::to('admin/c-receive/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              remove();
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

function formRequestTry(){
  let date=$('#date').val();
  let amount=($('#ammount').val() =='' ? '0.00' : $('#ammount').val());
  let customer=($('#customer').text() =='' ? "Not Selected" : $('#customer').text() );
  Swal.fire({
      title: 'Are you sure?',
      html: "<p >Customer : <b class='text-danger'>"+customer+"</b></p><p>Total Amount: <b class='text-danger'>"+amount+"</b> Date: <b class='text-danger'>"+date+"</b></p><p>You Want Save this ?</p>",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Yes Save It!'
    }).then((result) => {
      if (result.value==true) {
        formRequest();
      }
    })
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
      console.log(data)
      iterateData(data.data);
      $('#id').val(data.data.vinvoice.id);
      // var editKeys=Object.keys(data.data);
      // editKeys.forEach(function(key){
      //   if(key=='name'){
      //     $('#'+'name').val(data.data[key]);
      //   }
      //   if(key=='ammount'){
      //     $('#opening_balance').val(Math.abs(data.data[key]));
      //   }
      //    $('#'+key).val(data.data[key]);
      //    $('#modal').modal('show');
      //    $('#id').val(data.data.id);
      // })
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

  function remove(){
      $("input,textarea").removeClass('is-invalid').val('');
      $(".invalid-feedback").text('');
      $('#method').val(0).trigger('change')
      $('#customer').val(null).trigger('change')
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



  function changeMethod(){
    let method_type=$('#method').val()
    if(method_type==1){
        $('.bank').removeClass('d-none');
    }else{
        $('.bank').addClass('d-none');
    }
  }
  function iterateData(data){

    console.log(data);
  unique_number=0;
  arr=[];
    let html="";
    let html_id='';
    data.voucer.forEach(function(d){
          if(parseFloat(d.credit)!=0){
              html="<option value='"+d.subledger_id+"'>"+d.sub_name+"</option>";
              $('#customer').html(html);
              $('#ammount').val(d.credit);
              html_id+="<input type='hidden' id='v_id_cus' value='"+d.id+"'>"
              
              // console.log('ok')

              // html+="<input type='hidden' name='v_id[]' value='"+d.id+"' /><td><select class='form-control ledger' name='ledger[]'><option value='"+d.ledger_id+"'>"+d.name+"</option></select></td>";
              // html+="<td><select class='form-control subledger' name='subledger[]' id='subledger"+unique_number+"'><option value='"+d.subledger_id+"'>"+d.sub_name+"</option></select></td>";
              // html+="<td><input class='form-control debit' name='ammount[]' placeholder='0.00' value='"+d.credit+"'></td>";
              // html+="<td><input class='form-control comment' name='comment[]' placeholder='write comment' value='"+d.comment+"'></td>";
              // html+="<td><button class='btn btn-danger btn-sm remove'>X</button></td></tr>";
          }else{
              paymentMethod();
              html_id+="<input type='hidden' id='v_id_method' value='"+d.id+"'>"
              if(d.name=="Cash"){
                $('#method').val(0);
                $('#voucer_id').val(d.id);
                changeMethod();
              }else{
                $('#method').val(1);
                $('#cheque_no').val(d.cheque_no);
                $('#issue_date').val(dateFormat(parseInt(d.cheque_issue_date)*1000));
                $('#bank').html('<option value="'+d.subledger_id+'">'+d.sub_name+'</option>');
                $('#voucer_id').val(d.id);
                changeMethod();
              }
          }
        arr.push('#subledger'+unique_number);
        unique_number=unique_number+1;
    })
    $('#date').val(dateFormat(parseInt(data.vinvoice.date)*1000))
    $('#note').val(data.vinvoice.note);
    $('#data_id').html(html_id);
    $('#modal').modal('show');
    calculation();
  
}



  function dateFormat(data){
    date=new Date(data);
    let dates = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    let year = date.getFullYear();
    return(dates + "-" + month + "-" + year);
    // return date;
  }

</script>