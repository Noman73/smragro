<script>

window.supplierFormRequest= function(){
    $('input,select').removeClass('is-invalid');
    let name=$('#name').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let email=$('#email').val();
    let opening_balance=$('#opening_balance').val();
    let balance_type=$('#balance_type').val();
    let supplier_type=$('#supplier_type').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('name',name);
    formData.append('adress',adress);
    formData.append('phone',phone);
    formData.append('email',email);
    formData.append('opening_balance',opening_balance);
    formData.append('balance_type',balance_type);
    formData.append('supplier_type',supplier_type);
    $('#exampleModalLabel').text('Add New Supplier');
         axios.post("{{route('supplier.store')}}",formData)
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
    $('#exampleModalLabel').text('Add New Supplier');

});

function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
  $('form select').val('').niceSelect('update');
}

$("#supplier").select2({
    theme:'bootstrap4',
    placeholder:'suppliers',
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
  })
  let total_item=0;
  function addNew(){
    form=`<tr><td><select class="form-control product" name="product[]"></select></td>`;
    form+=`<td><input type="number" disabled class="form-control bg-secondary text-light" name="stock[]" placeholder='0.00'/></td>`;
    form+=`<td><input type="number" class="form-control" name="qantity[]" placeholder='0.00' value='1'/></td>`;
    form+=`<td><input type="number" class="form-control price" name="price[]" placeholder='0.00'/></td>`;
    form+=`<td><input type="number" class="form-control total" name="total[]" placeholder='0.00'/></td>`;
    form+=`<td><button class="btn btn-sm btn-danger removeItem" >X</button></td></tr>`;
   $("#item_table_body").append(form);
   initSelect2()
   total_item=total_item+1;
   $('#total-item').val(total_item)
  }

  $(document).ready(function(){
    addNew();
    supplierVisibility()
  })
  $(document).on('click','.removeItem',function(){
    $(this).parent().parent().remove();
    total_item=total_item-1;
   $('#total-item').val(total_item)
  })
  

  function calculation(){
  let x=0;
  let totalcal=0;
  var total_item=$('#total_item').val();
  var qantity=$("input[name='qantity[]']")
              .map(function(){return (($(this).val()=='')? 0:$(this).val());}).get();

 $("input[name='price[]']")
  .map(function(){
      price=(($(this).val()=='')? 0:$(this).val());
      console.log(qantity[x])
      total=(parseFloat(price)*parseFloat(qantity[x])).toFixed(2);
      if (!isNaN(total)) {
      $(this).parent().next().children("input[name='total[]']").val(total)
      totalcal+=parseFloat(total);
      $('#total').val(totalcal.toFixed(2));
      $('#total_payable').val(totalcal);
      // totalCalculation();
      }
    x=x+1;
  }).get();
}

$(document).on('change keyup','.price,#discount,input',function(e){
  console.log(e.target.name=='total[]')
  if(e.target.name=="total[]"){
    e.preventDefault();
  }else{
    calculation()
    totalCal();
  }
})
$(document).on('focusout','.total',function(){
  totalValDivision(this);
})
// $(document).on('change keyup','#discount',function(){
//   discountCheck()
// })
function totalValDivision(thisval){
   qantity=$(thisval).parent().prev().prev().children().val();
   console.log(qantity)
   total=$(thisval).val();
   price=total/qantity;
   $(thisval).parent().prev().children().val(price.toFixed(2));
   calculation()
   totalCal();
}
function initSelect2(){
  $(".product").select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-product-without-combo')}}",
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
        item=$("select[name='product[]'] option:selected")
                  .map(function(){return $(this).val();}).get();
         res=response.map(function(currentValue, index, arr){
          if (item.includes((currentValue.id).toString())){
            response[index]['disabled']=true;
          }
        });
        return {
          results:response,
        }
      },
      cache:true,
    }
  })
}

function totalCal(){
  var discountCheck=$('#discountCheck').prop('checked');
  discount=parseFloat($('#discount').val());
  vat=parseFloat($('#vat').val());
  transport=parseFloat($('#transport').val());
  if(isNaN(discount)){
    discount=0;
  }
  if(isNaN(vat)){
    vat=0;
  }
  if(isNaN(transport)){
    transport=0;
  }
  total=parseFloat($('#total').val());
  if(discountCheck){
    if(discount>100){
      $('#discount').val(100);
      totalCal();
    }
      total_discount=((total*discount)/100);
  }else{
      total_discount=discount;
  }
  vat=(total*vat)/100;
  console.log(total_discount,vat,transport)
  total_payable=(total+vat+transport)-(total_discount)
  $('#total_payable').val(total_payable)
  if($("#cash").is(':checked')){
     $('#ammount').val(total_payable);
  }
}
$('#date,#cheque_issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });

  function supplierVisibility(){
    cash_sale=$("#cash").is(":checked");
    regular_sale=$("#regular").is(":checked");
    if(cash_sale){
      $('#init-supplier').addClass('invisible')
      $('#init-supplier').removeClass('visible')
      $('#ammount').attr('disabled',true);
    }else if(regular_sale){
      $('#init-supplier').addClass('visible')
      $('#init-supplier').removeClass('invisible')
      $('#ammount').attr('disabled',false);
    }
  }

  function formRequest(){
    $('.submit').attr('disabled',true);
    $('input,select').removeClass('is-invalid');
    let warehouse=($('#warehouse').val()==null ? '' : $('#warehouse').val() );
    let supplier=$('#supplier').val();
    let chalan_no=$('#chalan_no').val();
    let purchase_type=$("input[name='purchase_type[]']:checked").val();
    let action=$("input[name='action[]']:checked").val();
    if(purchase_type==undefined){
      purchase_type='';
    }
    if(action==undefined){
      action='';
    }
    console.log(action);
    let total=$('#total').val();
    let total_item=$('#total-item').val();
    let discount=$('#discount').val();
    let vat=$('#vat').val();
    let transport=$('#transport').val();
    let total_payable=$('#total_payable').val();
    let transaction=$('#transaction').val();
    let ammount=$('#ammount').val();
    let date=$('#date').val();
    let note=$('#note').val();
    let staff_note=$('#staff_note').val();
    let product=$("select[name='product[]']").map(function(){
        return $(this).val();
    }).get();
    let qantity=$("input[name='qantity[]']").map(function(){
        return $(this).val();
    }).get();
    let price=$("input[name='price[]']").map(function(){
        return $(this).val();
    }).get();
    // payment method
    // let payment_method=$('#payment_method').val();
    // let cheque_no=$('#cheque_no').val();
    // let cheque_issue_date=$('#cheque_issue_date').val();
    // let cheque_photo=document.getElementById('cheque_photo').files;
    
    formData=new FormData()
    formData.append('purchase_type',purchase_type);
    formData.append('warehouse',warehouse);
    formData.append('action',action);
    formData.append('supplier',supplier);
    formData.append('chalan_no',chalan_no);
    formData.append('total',total);
    formData.append('total_item',total_item);
    formData.append('vat',vat);
    formData.append('discount',discount);
    formData.append('transport',transport);
    formData.append('total_payable',total_payable);
    // formData.append('payment_method',payment_method);
    formData.append('transaction',transaction);
    formData.append('ammount',ammount);
    formData.append('product',product);
    formData.append('qantity',qantity);
    formData.append('price',price);
    formData.append('note',note);
    formData.append('staff_note',staff_note);
    formData.append('date',date);
    // payment to voucer
    // formData.append('payment_method',payment_method);
    // formData.append('cheque_no',cheque_no);
    // formData.append('cheque_issue_date',cheque_issue_date);
    // if(cheque_photo[0]!=null){
    //   formData.append('cheque_photo',cheque_photo[0]);
    // }
    axios.post('admin/purchase',formData)
    .then(response=>{
      console.log(response);
        if(response.data.message){
            $('.submit').attr('disabled',false);
            toastr.success(response.data.message);
            Clean();
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
  function formRequestTry(){
    let date=$('#date').val();
    let amount=($('#total_payable').val() =='' ? '0.00' : $('#total_payable').val());
    Swal.fire({
        title: 'Are you sure?',
        html: "<p>Total Payable: <b class='text-danger'>"+amount+"</b> Date: <b class='text-danger'>"+date+"</b></p><p>You Want Save this ?</p>",
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
  $("#warehouse").select2({
    theme:'bootstrap4',
    placeholder:'Warehouse',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-warehouse')}}",
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
    },
    initSelection: function(element, callback) {
      var id = $(element).val();
        if(id !== "") {
            $.ajax("{{URL::to('/admin/get-warehouse')}}", {
                type:'post',
                data: {_token:"{{csrf_token()}}"},
                dataType: "json"
            }).done(function(data) {
                option="<option value='"+data[0].id+"'>"+data[0].text+"</option>";
                // callback(data);
                console.log(option)
                $('#warehouse').html(option);
                callback(data)
            });
        }
      }
  });
  $("#payment_method").select2({
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

  function bank(){
    let bank_id=$('#payment_method').val();
    $('#cheque_no').val('');
    $('#cheque_photo').val('');
    axios.get('/admin/get-bank-details/'+bank_id)
    .then(res=>{
      console.log(res.data.account_type)
      if(res.data.account_type==1){
        $(".cheque").removeClass('d-none')
      }else{
        $(".cheque").addClass('d-none')
      }
    })
  }
// function validate(element,item_name,rules){
//   value=element.val();
//   ruleArr=rules.split('|');

//   messages={
//     required:"the "+item_name+" is required",
//     invalid:"the "+item_name+" is invalid",
//   }
//   return messages[rules];
// }

function balance(thisval){
  id=$(thisval).val();
  console.log(id);
  axios.get('admin/accounts/get-supplier-balance/'+id)
  .then((res)=>{
      console.log(res.data[0].total);
      if(parseFloat(res.data[0].total)<0){
        $('#supplier-balance').text(res.data[0].total);
        $('#supplier-balance').addClass('text-danger')
        $('#supplier-balance').removeClass('text-success')
      }else if(parseFloat(res.data[0].total)>=0){
        $('#supplier-balance').text(res.data[0].total);
        $('#supplier-balance').addClass('text-success');
        $('#supplier-balance').removeClass('text-danger');
      }else{
        $('#supplier-balance').text('0.00');
        $('#supplier-balance').addClass('text-success');
        $('#supplier-balance').removeClass('text-danger');
      }
      if(id!=null){
          $('.total_balance').removeClass('d-none')
      }
  })
}
function validation(){

}
 $('#supplierModal').on("click",function(){
   $('#modal').modal('show');
 })

function Clean(){
    $('#add_product tbody').empty();
    $("input[type='text']").val('')
    $("input[type='number']").val('')
    $("#note,#staff_note").val('')
    $('select').val('').trigger('change')
    total_item=0;
    addNew();
    $('#date,#cheque_issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
  console.log('xyz')
}
</script>
