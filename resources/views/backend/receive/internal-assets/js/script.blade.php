<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('receive.index')}}"
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
    $('.submit').attr('disabled',true);
    $('input,select').removeClass('is-invalid');
    v_id=$("input[name='v_id[]']").map(function(){
        return $(this).val();
    }).get();
    ledger=$("select[name='ledger[]']").map(function(){
        return $(this).val();
    }).get();
    subledger=$("select[name='subledger[]']").map(function(){
        return ($(this).val()==null ? 'null' : $(this).val());
    }).get();
    ammount=$("input[name='ammount[]']").map(function(){
        return $(this).val();
    }).get();
    comment=$("input[name='comment[]']").map(function(){
        return $(this).val();
    }).get();
    console.log(ledger,subledger)
    let method=$('#method').val();
    let method_voucer=$('#voucer_id').val();
    let date=$('#date').val();
    let bank=$('#bank').val();
    let supplier=$('#supplier').val();
    // let ammount=$('#ammount').val();
    let cheque_no=$('#cheque_no').val();
    let cheque_photo=$('#cheque_photo').val();
    let issue_date=$('#issue_date').val();
    let note=$('#note').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('ledger',ledger);
    formData.append('subledger',subledger);
    formData.append('ammount',ammount);
    formData.append('comment',comment);
    formData.append('method',method);
    formData.append('date',date);
    formData.append('bank',bank);
    formData.append('cheque_no',cheque_no);
    formData.append('issue_date',issue_date);
    formData.append('cheque_photo',cheque_photo);
    formData.append('note',note);
    $('#exampleModalLabel').text('Add New Payment');
    if(id!=''){
      formData.append('_method','PUT');
      formData.append('v_id',v_id);
      formData.append('method_voucer',method_voucer);
    }
    //axios post request
    if (id==''){
         axios.post("{{route('receive.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                remove();
                $('#modal').modal('hide');
                calculation();
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
      axios.post("{{URL::to('admin/receive/')}}/"+id,formData)
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
      iterateData(data.data);
      $('#id').val(data.data.vinvoice.id);
      // var editKeys=Object.keys(data.data);
      // console.log(data);
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
      html+="<input type='hidden' name='v_id[]' value='0'><td><select class='form-control ledger' name='ledger[]'></select></td>";
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
function changeMethod(){
  let method_type=$('#method').val()
  if(method_type==1){
      $('.bank').removeClass('d-none');
  }else{
      $('.bank').addClass('d-none');
  }
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
  $(document).on('change','.ledger', function (e) {
    console.log($(this));
    $(this).parent().next().children().val(null).trigger('change');
    console.log($(this).parent().next().children().val());
  });

function iterateData(data){
  console.log('x')
  unique_number=0;

  arr=[];
  console.log(data)
    let html="<tr>";
    data.voucer.forEach(function(d){
          if(parseFloat(d.credit)!=0){
              html+="<input type='hidden' name='v_id[]' value='"+d.id+"' /><td><select class='form-control ledger' name='ledger[]'><option value='"+d.ledger_id+"'>"+d.name+"</option></select></td>";
              html+="<td><select class='form-control subledger' name='subledger[]' id='subledger"+unique_number+"'><option value='"+d.subledger_id+"'>"+d.sub_name+"</option></select></td>";
              html+="<td><input class='form-control debit' name='ammount[]' placeholder='0.00' value='"+d.credit+"'></td>";
              html+="<td><input class='form-control comment' name='comment[]' placeholder='write comment' value='"+d.comment+"'></td>";
              html+="<td><button class='btn btn-danger btn-sm remove'>X</button></td></tr>";
          }else{
              paymentMethod();
              if(d.name=="Cash"){
                $('#method').val(0);
                $('#voucer_id').val(d.id);
                changeMethod()
              }else{
                $('#method').val(1);
                $('#cheque_no').val(d.cheque_no);
                $('#issue_date').val(dateFormat(parseInt(d.cheque_issue_date)*1000));
                $('#bank').html('<option value="'+d.subledger_id+'">'+d.sub_name+'</option>');
                changeMethod();
                $('#voucer_id').val(d.id);
              }
          }
        
        arr.push('#subledger'+unique_number);
        unique_number=unique_number+1;
    })
    $('#date').val(dateFormat(parseInt(data.vinvoice.date)*1000))
    $('#payment-body').html(html);
    $('#modal').modal('show');
    calculation();
    initSelect2();
    arr.forEach((thisArr)=>{
          $(thisArr).select2({
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
                console.log($(this).parent().prev().children().text())
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
    })
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