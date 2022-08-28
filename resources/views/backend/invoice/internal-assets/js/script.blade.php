<script>

window.customerFormRequest= function(){
    $('input,select').removeClass('is-invalid');
    let name=$('#name').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let email=$('#email').val();
    let opening_balance=$('#opening_balance').val();
    let balance_type=$('#balance_type').val();
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
  })
  let total_item=0;
  function addNew(){
    form=`<tr><td><select class="form-control product" name="product[]"></select></td>`;
    form+=`<td><input type="number" disabled class="form-control bg-secondary text-light" name="stock[]" placeholder='0.00'/></td>`;
    form+=`<td><input type="number" class="form-control qantity" name="qantity[]" placeholder='0.00' value='1'/></td>`;
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
    customerVisibility()
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

$(document).on('change keyup','.price,#discount,#vat,.qantity,#transport,.product',function(e){
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
      url:"{{URL::to('/admin/get-product')}}",
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

  function customerVisibility(){
    // console.log('123456')
    // cash_sale=$("#cash").is(":checked");
    // regular_sale=$("#regular").is(":checked");
    sale_type=$('#sale_type').val()
    if(sale_type==0){
      $('#init-customer').addClass('invisible')
      $('#init-customer').removeClass('visible')
      $("#w_customer").removeClass('d-none');
      $("#payment_method_row").addClass('d-none');
      $('#ammount').attr('disabled',true);
      $('#ammount').parent().parent().removeClass('d-none');
    }else if(sale_type==1){
      $('#init-customer').addClass('visible')
      $('#init-customer').removeClass('invisible')
      $('#ammount').attr('disabled',false);
      $('#ammount').parent().parent().addClass('d-none');
      $("#payment_method_row").addClass('d-none');
      $("#w_customer").addClass('d-none');
      $("#w_mobile").val('');
    }else if(sale_type==2){
      $('#init-customer').addClass('invisible')
      $('#init-customer').removeClass('visible')
      $("#payment_method_row").removeClass('d-none');
      $("#w_customer").removeClass('d-none');
      $('#ammount').attr('disabled',false);
      $("#w_mobile").val('');
      $('#ammount').parent().parent().removeClass('d-none');
    }
  }
  $('#sale_type').change(function(){
    $('#customer').val('').trigger('change')
    $('.total_balance').addClass('d-none');
  });
  function formRequest(){
    $('.submit').attr('disabled',true);
    $('input,select').removeClass('is-invalid');
    let discountCheck=$('#discountCheck').is(':checked');
    console.log(discountCheck);
    if(discountCheck){
      discount_type=1;
    }else{
      discount_type=0;
    }
    let discount=$('#discount').val();
    let customer=$('#customer').val();
    let sale_type=$("#sale_type").val();
    let action=$("input[name='action[]']:checked").val();
    if(sale_type==undefined){
      sale_type='';
    }
    if(action==undefined){
      action='';
    }
    console.log(action);
    let total=$('#total').val();
    let total_item=$('#total-item').val();
    let vat=$('#vat').val();
    let transport=$('#transport').val();
    let total_payable=$('#total_payable').val();
    let transaction=$('#transaction').val();
    let ammount=$('#ammount').val();
    let date=$('#date').val();
    let hand_bill=$('#hand_bill').val();
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
    // walking customer
    let w_name=$('#w_name').val();
    let w_mobile=$('#w_mobile').val();
    let w_adress=$('#w_adress').val();
    // payment method
    let payment_method_type=$("input[name='payment_method_type[]']:checked").val();
    let payment_method=$('#bank').val();
    let cheque_no=$('#cheque_no').val();
    let cheque_issue_date=$('#cheque_issue_date').val();
    let cheque_photo=document.getElementById('cheque_photo').files;
    // courier
    let courier=$('#courier').val();
    formData=new FormData()
    formData.append('sale_type',sale_type);
    formData.append('discount_type',discount_type);
    formData.append('discount',discount);
    formData.append('action',action);
    formData.append('customer',customer);
    formData.append('total',total);
    formData.append('total_item',total_item);
    formData.append('vat',vat);
    formData.append('transport',transport);
    formData.append('total_payable',total_payable);
    formData.append('payment_method_type',payment_method_type);
    formData.append('transaction',transaction);
    formData.append('ammount',ammount);
    formData.append('product',product);
    formData.append('qantity',qantity);
    formData.append('price',price);
    formData.append('note',note);
    formData.append('staff_note',staff_note);
    formData.append('date',date);
    formData.append('hand_bill',hand_bill);
    // walking customer
    formData.append('name',w_name);
    formData.append('mobile',w_mobile);
    formData.append('adress',w_adress);
    // payment to voucer
    formData.append('payment_method',payment_method);
    formData.append('cheque_no',cheque_no);
    formData.append('cheque_issue_date',cheque_issue_date);
    if(cheque_photo[0]!=null){
      formData.append('cheque_photo',cheque_photo[0]);
    }
    // courier
    formData.append('courier',courier);
    axios.post('admin/invoice',formData)
    .then(response=>{
      console.log(response);
        if(response.data.message){
            toastr.success(response.data.message);
            Clean();
            $('.submit').attr('disabled',false);
            window.location="{{URL::to('admin/view-pages/sales-invoice')}}/"+response.data.id;
        }else if(response.data.error){
          $('.submit').attr('disabled',false);
            var keys=Object.keys(response.data.error);
            keys.forEach(function(d){
              if(d=='mobile' || d=='name'){
                $('#w_'+d).addClass('is-invalid')
              }else{
                $('#'+d).addClass('is-invalid')
              }
              $('#'+d+'_msg').text(response.data.error[d][0]);
          })
        }
    })
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
  $("#courier").select2({
    theme:'bootstrap4',
    placeholder:'Select Courier',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-shipping-company')}}",
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
    let method_type=$("input[name='payment_method_type[]']:checked").val();
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
function validate(item_name){
  messages={
    required:"the "+item_name+" is required",
    invalid:"the "+item_name+" is invalid",
  }
}
function Clean(){
    total_item=0;
    $('#add_product tbody').empty();
    $("input[type='text']").val('')
    $("input[type='number']").val('')
    $("#note,#staff_note").val('')
    $('select').val('').trigger('change')
    $('#sale_type').val(0).trigger('change')
    addNew();
    $('#date,#cheque_issue_date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
}

// function saleTypeCheck(){
//   sale_type=$("input[name='sale_type[]']:checked").val();
//   if(sale_type==0){
//     $
//   }
// }

function balance(thisval){
  id=$(thisval).val();
  console.log(id);
  axios.get('admin/accounts/get-customer-balance/'+id)
  .then((res)=>{
      console.log(res.data[0].total);
      if(parseFloat(res.data[0].total)<0){
        $('#customer-balance').text(res.data[0].total);
        $('#customer-balance').addClass('text-danger')
        $('#customer-balance').removeClass('text-success')
      }else if(parseFloat(res.data[0].total)>=0){
        $('#customer-balance').text(res.data[0].total);
        $('#customer-balance').addClass('text-success');
        $('#customer-balance').removeClass('text-danger');
      }else{
        $('#customer-balance').text('0.00');
        $('#customer-balance').addClass('text-success');
        $('#customer-balance').removeClass('text-danger');
      }
      if(id!=null){
          $('.total_balance').removeClass('d-none')
      }
  })
}

$(document).on('select2:unselect','#customer',function(){
    $('.total_balance').addClass('d-none')
})
$(document).on('change keyup','#w_mobile',function(){
   $('#w_name').val('');
   $('#w_adress').val('');
   mobile=$(this).val();
   axios.post(baseURL+'/admin/check-customer',{mobile:mobile})
   .then(res=>{
     console.log(res);
     if(res.data.exist){
       $('#w_mobile').val(res.data.exist.phone)
       $('#w_name').val(res.data.exist.name)
       $('#w_adress').val(res.data.exist.adress)
     }
   })
})

function saleByCheck(){
    sale_by_self=$("#sale_by_self").is(":checked");
    sale_by_courier=$("#sale_by_courier").is(":checked");
    if(sale_by_self){
      $('#courier-list').addClass('d-none')
      $('#ammount').attr('disabled',true);
    }else if(sale_by_courier){
      $('#courier-list').removeClass('d-none')
      $('#ammount').attr('disabled',false);
    }
}

$('body').on('select2:select',"select[name='product[]']", function (e){
  id=e.params.data.id;
  this_cat=$(this);
  customer=$('#customer').val();
  axios.get('admin/get-quantity/'+id)
      .then(function(response){
            console.log(response)
            this_cat.parent().next().children("[name='stock[]']").val(response.data.total);
          })
          .catch(function(error){
          console.log(error.request);
        })
  axios.post('admin/get-product-price',{customer:customer,product:id})
   .then(res=>{
    console.log(res);
    this_cat.parent().next().next().next().children("[name='price[]']").val(parseFloat(res.data).toFixed(2));
    
   })
 })


//  $(document).on('select2:select',"select[name='product[]']", function (e){

   
//    product=$(this).val();
//    console.log(customer,product);
   
//  })


</script>
