<script>

window.customerFormRequest= function(){
    $('input,select').removeClass('is-invalid');
    let name=$('#name').val();
    let adress=$('#adress').val();
    let phone=$('#phone').val();
    let email=$('#email').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('name',name);
    formData.append('adress',adress);
    formData.append('phone',phone);
    formData.append('email',email);
    $('#exampleModalLabel').text('Add New Customer');
         axios.post("{{route('customer.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                Clean();
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
                callback()
            });
        }
      }
  });
  $("#category").select2({
    theme:'bootstrap4',
    placeholder:'Category',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-category')}}",
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
  function addNew(product_id,name,qantity,price){
    // sound
    
    product=$('#'+product_id)
    dom_qty=product.parent().next().next().children().val();
    console.log(dom_qty);

  // sound end
  if((parseFloat(qantity)==0 || parseFloat(dom_qty)>=parseFloat(qantity))){
      notAdd();
      return false;
    }
  var sound = document.getElementById("audio");
   sound.play();
    exist=$("input[name='product[]'][value='"+product_id+"']");
    if(exist.val()==product_id){
      qty=exist.parent().next().next().children("input[name='qantity[]']")
      qty.val(parseFloat(qty.val())+1)
      calculation()
      totalCal();
      return false;
    }
    
    form=`<tr><td>`+name+`<input id="`+product_id+`" class="form-control d-none" name="product[]" value="`+product_id+`"></td>`;
    form+=`<td><input type="text" disabled class="form-control form-control-sm bg-secondary text-light" name="stock[]" value="`+qantity+`" placeholder='0.00'/></td>`;
    form+=`<td><input type="number" class="form-control form-control-sm qantity" name="qantity[]" placeholder='0.00' value='1'/></td>`;
    form+=`<td><input type="number" class="form-control form-control-sm price" name="price[]" value="`+price+`" placeholder='0.00'/></td>`;
    form+=`<td><input type="number" class="form-control form-control-sm total" name="total[]" placeholder='0.00'/></td>`;
    form+=`<td><button class="btn btn-sm btn-danger removeItem" >X</button></td></tr>`;
   $("#item_table_body").append(form);
   initSelect2()
   total_item=total_item+1;
   $('#total-item').val(total_item)
   calculation()
   totalCal();
  }

  $(document).ready(function(){
    customerVisibility()
    showProduct();
    $('#barcode').focus();
    $('body').addClass('sidebar-collapse')
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
$(document).on('keyup change','#search',function(){
  showProduct();
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
  console.log($("#customer").val())
  if($("#customer").val()==null){
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
    customer=$('#customer').val()
    if(customer==null){
      $('#ammount').attr('disabled',true);
    }else if(sale_type==1){
      $('#ammount').attr('disabled',false);
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
    let ammount=$('#ammount').val();
    let date=$('#date').val();
    let hand_bill=$('#hand_bill').val();
    let note=$('#note').val();
    let staff_note=$('#staff_note').val();
    let product=$("input[name='product[]']").map(function(){
        return $(this).val();
    }).get();
    let qantity=$("input[name='qantity[]']").map(function(){
        return $(this).val();
    }).get();
    let price=$("input[name='price[]']").map(function(){
        return $(this).val();
    }).get();
    // payment method
    let payment_method_type=$("input[name='payment_method_type[]']:checked").val();
    let payment_method=$('#bank').val();
    let cheque_no=$('#cheque_no').val();
    let cheque_issue_date=$('#cheque_issue_date').val();
    let cheque_photo=document.getElementById('cheque_photo').files;
    let warehouse=$('#warehouse').val()
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
    formData.append('ammount',ammount);
    formData.append('product',product);
    formData.append('qantity',qantity);
    formData.append('price',price);
    formData.append('date',date);
    formData.append('warehouse',warehouse);
    // payment to voucer
    formData.append('payment_method',payment_method);
    formData.append('cheque_no',cheque_no);
    formData.append('cheque_issue_date',cheque_issue_date);
    if(cheque_photo[0]!=null){
      formData.append('cheque_photo',cheque_photo[0]);
    }
    // courier
    axios.post("{{route('pos.store')}}",formData)
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
  $("#note").select2({
    theme:'bootstrap4',
    placeholder:'Select Note',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-multi-note')}}",
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
    function clearSaleBy(){
      $('#courier-list').val(null).trigger('change');
      $('#shipping_name,#shipping_mobile,#shipping_adress,#condition_amount').val('');
    }
    checkedInput= $('input[name="sale_by[]"]:checked');
    console.log(checkedInput.val())
    switch (parseInt(checkedInput.val())) {
      case 0:
      $('#courier-list').addClass('d-none')
      $('.shipping').addClass('d-none')
      $('#ammount').attr('disabled',true);
      clearSaleBy();
      break;
      case 1:
      $('#courier-list').removeClass('d-none')
      $('.shipping').addClass('d-none')
      $('#ammount').attr('disabled',true);
      break;
      case 2:
      $('.shipping').removeClass('d-none')
      $('#courier-list').removeClass('d-none')
      break;
      default:
        break;
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
    calculation()
    totalCal();
   })
 })


//  $(document).on('select2:select',"select[name='product[]']", function (e){

   
//    product=$(this).val();
//    console.log(customer,product);
   
//  })

// $(document).keypress(function(event) {
//     if(event.keyCode==100 && !event.shiftKey){
//       addNew()
//       return false
//     }
    
// });
$(document).keypress(function(event){
 console.log(event.which)
  if(event.keyCode==68){
      addNew();
  }
  if(event.keyCode==65){
    cond=false;
    let product=$("select[name='product[]']").map(function(){
        if($(this).val()==null && cond===false){
          $(this).focus();
          $(this).select2('open');
          cond=true;
        }
    });
    
  }
})
function showProduct(){
  category=$('#category').val();
  search=$('#search').val();
  brand=$('#brand').val();
    axios.post("{{URL::to('admin/get-all-products')}}",{category:category,brand:brand,search:search})
    .then(res=>{
      console.log(res)
      product='';
      res.data.forEach(function(d){
       product+= `<div class="col-12 col-md-3">
                    <div onclick="addNew(`+d.id+`,'`+d.name.replace('"','“')+`','`+d.qantity+`',`+d.sale_price+`)" class="card rounded" style="min-height:120px;">
                      <div class="container">
                        <p style="font-size:10px;" class="bg-danger pl-1 mt-2">Quantity : `+d.qantity+`</p>
                        <center style="font-size:10px;">
                        <img style="max-height:50px;" class="img-fluid" src="{{asset('storage/product/')}}/`+(d.image!=null ? d.image : 'no-image.png')+`" alt="sdf">
                        </center>
                        <p style="font-size:10px;text-align:center;font-weight:bold">`+d.name+`</p>
                        <p class="font-weight-bold text-center bg-primary p-1" style="font-size:10px;">৳ `+d.sale_price+`</p>
                      </div>
                    </div>
                  </div>
                `
      })
      $('.show-product').html(product)
    })
}
$(document).on('keyup','#barcode',function(){
  code=$(this).val();
  axios.get("{{URL::to('admin/get-product-by-code')}}/"+code)
  .then(res=>{
    console.log(res);
    data=res.data[0];
    $('#barcode').val('');
    addNew(data.id,data.name.replace('"','“'),data.qantity,data.sale_price)
    return false;
  })
})
function notAdd(){
  sound2 = document.getElementById("audio2");
  sound2.play();
}
</script>
