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
  }
  function itemClear(){
    $('#brand').val(null).trigger('change');
    $('#model').val(null).trigger('change');
    $('#product').val(null).trigger('change');
    $('#part_id').val(null).trigger('change');
    $('#quantity').val('');
    $('#b_rate').val('');
    $('#mltp').val('');
    $('#amount').val('');
    $('#total-amt').val('');
    $('#stock').val('');
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
    let total_item=0;
    function addNew(){
  
      let item=$('#product').val();
      let itemtext=$('#product option:selected').text();
      let brandtext=$('#brand option:selected').text();
      let modeltext=$('#model option:selected').text();
      let quantity=$('#quantity').val();
      let part_id=$('#part_id').val();
      let amount=$('#amount').val();
      let total=$('#total-amt').val();
      if(amount=='' || item=='' || part_id==''){
        return false;
      }
      let cond=true;
      product=$("input[name='product[]']").map(function(){
          $(this).val();
          if($(this).val()==item){
            qtyval=$(this).parent().next().children().val();
            $(this).parent().next().children().val(parseFloat(qtyval)+1)
            cond=false;
          }
      }).get()
      if(!cond){
        return false;
      }
      form=`<tr><td><input type="hidden" name="product[]" value="`+item+`">`+itemtext+`<br/> `+part_id+`</td>`;
      form+=`<td>`+brandtext+`<br/>`+modeltext+`</td>`
      form+=`<td><input type="number" class="form-control form-control-sm qantity" name="qantity[]" placeholder='0.00' value='`+quantity+`' /></td>`;
      form+=`<td><input type="number" class="form-control form-control-sm price" name="price[]" placeholder='0.00' value="`+amount+`" disabled/></td>`;
      form+=`<td><input type="number" class="form-control form-control-sm total" name="total[]" placeholder='0.00' value="`+total+`" disabled/></td>`;
      form+=`<td><button class="btn btn-sm btn-danger removeItem" >X</button></td></tr>`;
     $("#item_table_body").append(form);
     initSelect2()
     total_item=total_item+1;
     $('#total-item').val(total_item)
     itemClear();
     calculation();
     totalCal();
     $('#product').select2('open');
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
  
  $(document).on('change keyup','.price,#discount,#vat,.qantity,#transport,.product,#discountCheck,.mtp,.b_rate,#ammount',function(e){
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
    previous_due=($('#previous_due').val()=='' ? 0.00 : $('#previous_due').val());
    amount=($('#ammount').val()=='' ? 0.00 : $('#ammount').val());
    console.log('ssf'+previous_due,total_payable,amount)
    $('#current_due').val(((total_payable+parseFloat(previous_due))-parseFloat(amount)).toFixed(2));
    if($("#cash").is(':checked')){
      if($('#sale_type').val()==0){
        $('#ammount').val(total_payable);
      }
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
      sale_type=$('#sale_type').val()
      if(sale_type==0){
        $('#init-customer').addClass('invisible')
        $('#init-customer').removeClass('visible')
        $("#w_customer").removeClass('d-none');
        $("#payment_method_row").addClass('d-none');
        $('#ammount').attr('disabled',true);
        $('#ammount').parent().parent().removeClass('d-none');
        $('.due').addClass('d-none');
      }else if(sale_type==1){
        $('#init-customer').addClass('visible')
        $('#init-customer').removeClass('invisible')
        // $('#ammount').attr('disabled',false);
        // $('#ammount').parent().parent().addClass('d-none');
        // $("#payment_method_row").addClass('d-none');
        $("#w_customer").addClass('d-none');
        $("#w_mobile").val('');
        // 
        $('.due').removeClass('d-none')
        $("#payment_method_row").removeClass('d-none');
        $('#ammount').attr('disabled',false);
        $('#ammount').parent().parent().removeClass('d-none');
        $('#ammount').val('');
      }else if(sale_type==2){
        $('#init-customer').addClass('invisible')
        $('#init-customer').removeClass('visible')
        $("#payment_method_row").removeClass('d-none');
        $("#w_customer").removeClass('d-none');
        $('#ammount').attr('disabled',false);
        $("#w_mobile").val('');
        $('#ammount').parent().parent().removeClass('d-none');
        $('#ammount').val('');
        $('.due').addClass('d-none');
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
      let warehouse=($('#warehouse').val()==null ? '' : $('#warehouse').val() );
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
      let product=$("input[name='product[]']").map(function(){
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
      let shipping_name=$('#shipping_name').val();
      let shipping_mobile=$('#shipping_mobile').val();
      let shipping_adress=$('#shipping_adress').val();
      let condition_amount=$('#condition_amount').val();
      let sale_by= $('input[name="sale_by[]"]:checked').val();
      formData=new FormData()
      formData.append('sale_type',sale_type);
      formData.append('warehouse',warehouse);
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
      formData.append('shipping_name',shipping_name);
      formData.append('shipping_mobile',shipping_mobile);
      formData.append('shipping_adress',shipping_adress);
      formData.append('condition_amount',condition_amount);
      formData.append('sale_by',sale_by);
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
                  callback()
              });
           }
        }
    });
  
  
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
    $("#model").select2({
      theme:'bootstrap4',
      placeholder:'Model',
      allowClear:true,
      ajax:{
        url:"{{URL::to('/admin/get-model')}}",
        type:'post',
        dataType:'json',
        delay:20,
        data:function(params){
          return {
            searchTerm:params.term,
            brand_id:$('#brand').val(),
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
    $("#product").select2({
      theme:'bootstrap4',
      placeholder:'Item Name',
      allowClear:true,
      ajax:{
        url:"{{URL::to('/admin/get-product-by-data')}}",
        type:'post',
        dataType:'json',
        delay:20,
        data:function(params){
          return {
            searchTerm:params.term,
            brand_id:$('#brand').val(),
            model_id:$('#model').val(),
            part_id:$('#part_id').val(),
            _token:"{{csrf_token()}}",
            }
        },
        processResults:function(response){
          return {
            results:response,
          }
        },
        cache:false,
      }
    });
    $("#part_id").select2({
      theme:'bootstrap4',
      placeholder:'Part ID',
      allowClear:true,
      ajax:{
        url:"{{URL::to('/admin/get-part-id')}}",
        type:'post',
        dataType:'json',
        delay:20,
        data:function(params){
          return {
            searchTerm:params.term,
            brand_id:$('#brand').val(),
            model_id:$('#model').val(),
            part_id:$('#part_id').val(),
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
    $("#market").select2({
      theme:'bootstrap4',
      placeholder:'Market',
      allowClear:true,
      ajax:{
        url:"{{URL::to('/admin/get-market')}}",
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
        comment=res.data[0].comment;
        if(comment==null){
          comment='';
        }
        if($('#sale_type').val()==1){
          $('#previous_due').val(res.data[0].total+' '+comment);
        }
        if(parseFloat(res.data[0].total)<0){
          $('#customer-balance').text(res.data[0].total+' '+comment);
          $('#customer-balance').addClass('text-danger')
          $('#customer-balance').removeClass('text-success')
        }else if(parseFloat(res.data[0].total)>=0){
          $('#customer-balance').text(res.data[0].total+' '+comment);
          $('#customer-balance').addClass('text-success');
          $('#customer-balance').removeClass('text-danger');
        }else{
          $('#customer-balance').text('0.00'+' '+comment);
          $('#customer-balance').addClass('text-success');
          $('#customer-balance').removeClass('text-danger');
        }
        if(id!=null){
            $('.total_balance').removeClass('d-none')
        }
    })
  }
  $(document).on('select2:unselect','#customer',function(){
      $('.total_balance').addClass('d-none');
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
      $('#courier').val(null).trigger('change')
      courierSelection();
      function clearSaleBy(){
        $('#courier-list').val(null).trigger('change');
        $('#shipping_name,#shipping_mobile,#shipping_adress,#condition_amount').val('');
      }
      checkedInput= $('input[name="sale_by[]"]:checked');
      console.log(checkedInput.val())
      switch (parseInt(checkedInput.val())) {
        case 0:
        $('#courier-list').addClass('d-none');
        $('.shipping').addClass('d-none');
        $('#ammount').attr('disabled',true);
        clearSaleBy();
        break;
        case 1:
        $('#courier-list').removeClass('d-none')
        $('.shipping').addClass('d-none')
        $('#ammount').attr('disabled',false);
        break;
        case 2:
        $('.shipping').removeClass('d-none')
        $('#courier-list').removeClass('d-none')
        break;
        default:
          break;
      }
  }
  
  $('body').on('select2:select',"#product", function (e){
    id=e.params.data.id;
    this_cat=$(this);
    customer=$('#customer').val();
    axios.get('admin/get-quantity/'+id)
        .then(function(response){
              console.log(response)
              $('#stock').val(response.data.total);
              this_cat.parent().next().children("[name='stock[]']").val(response.data.total);
            })
            .catch(function(error){
            console.log(error.request);
          })
    axios.get('admin/get-product-sale-price/'+id)
     .then(res=>{
      console.log(res);
      $('#b_rate').val(parseFloat(res.data).toFixed(2));
      this_cat.parent().next().next().next().children("[name='b_rate[]']").val(parseFloat(res.data).toFixed(2));
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
   if(event.keyCode==13){
        $('.item-details').css('visibility','hidden')
    }
    if(event.keyCode==68){
        addNew();
        $('.item-details').css('visibility','hidden')
    }
    if(event.keyCode==9){
        $(document.activeElement).parent().parent().prev().select2('open')
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
  $(document).keydown(function(e) {
    if(e.keyCode==9){
      setTimeout(() => {
        el=$(document.activeElement).parent().parent().prev();
        if(el.val()==null){
          el.select2('open')
        }
        
      }, 250);
     
    }
  });
  function singleCalc(){
     qty=$('#quantity').val();
     brate=$('#b_rate').val();
     mltp=$('#mltp').val();
     amount=(parseFloat(brate)*parseFloat(mltp)).toFixed(2);
     total=parseFloat(qty)*parseFloat(amount);
     $('#amount').val(amount);
     $('#total-amt').val(total);
  }
  
  $(document).on('change keyup','#quantity,#b_rate,#mltp',function(){
    singleCalc();
  })
  
  
  // $('body').on('select2:select',"#product", function (e){
  //   id=$(this).val();
  //   console.log(id)
  //   id
  //   axios.get("{{URL::to('/admin/selected-product-data')}}/"+id)
  //   .then(res=>{
  //     console.log(res);
  //     model="<option value='"+res.data.model.id+"'>"+res.data.model.name+"</option>";
  //     brand="<option value='"+res.data.brand.id+"'>"+res.data.brand.name+"</option>";
  //     part_id="<option value='"+res.data.part_id+"'>"+res.data.part_id+"</option>";
  //     $('#model').html(model);
  //     $('#brand').html(brand);
  //     $('#part_id').html(part_id);
  //     $('#brand').trigger('select2:select');
  //     singleCalc()
  //   })
  // })
  $('body').on('select2:select',"#part_id", function (e){
    id=$(this).val();
    console.log(id)
    axios.get('admin/get-quantity/'+id)
        .then(function(response){
              console.log(response)
              $('#stock').val(response.data.total);
              this_cat.parent().next().children("[name='stock[]']").val(response.data.total);
            })
            .catch(function(error){
            console.log(error.request);
          })
    axios.get("{{URL::to('/admin/part-id-product-data')}}/"+id)
    .then(res=>{
      console.log(res);
      model="<option value='"+res.data.model.id+"'>"+res.data.model.name+"</option>";
      brand="<option value='"+res.data.brand.id+"'>"+res.data.brand.name+"</option>";
      product="<option value='"+res.data.id+"'>"+res.data.name+"</option>";
      $('#model').html(model);
      $('#brand').html(brand);
      $('#product').html(product);
      $('#brand').trigger('select2:select');
      $('#b_rate').val(res.data.sale_price)
      singleCalc()
    })
  })
  
  $('body').on('select2:select',"#brand",function(e){
    id=$(this).val();
    console.log($(this).val())
    axios.get("{{URL::to('admin/brand-multiply')}}/"+id)
    .then(res=>{
      console.log(res);
      $('#mltp').val(res.data)
      singleCalc()
      getMultiply();
    })
  })
  function select2Open(thisval){
    $(thisval).trigger('select2:open');
  }
  
  // function searchText(text){
  //   console.log(text)
  //       // $('td').css('background','#ffffff');
  //       el=$("td:contains('"+text+"')")
  //       el
  //       for (let index = 0; index < el.length; index++) {
  //         elofdoc = el[index];
  //         elofdoc.parentElement.style.background = "green";
  //         // elofdoc.parentElement.style.background = "red";
  //         console.log(elofdoc)
  //       }
  // }
  $(document).on('select2:select',"#product", function (e){
    initDetails();
    if(part_id!=null){
      $('#addnewbtn').attr('disabled',true);
    }
  })
  var initDetailsStatus=false;
  function initDetails(){
     initDetailsStatus=true;
     text=$('#product option:selected').text();
     brand=$('#brand option:selected').val();
     model=$('#model option:selected').val();
     part_id=$('#part_id option:selected').text();
     axios.post("{{URL::to('admin/product-details')}}",{text:text,brand:brand,model:model})
     .then((res)=>{
        console.log(res);
        html="";
        res.data.forEach(function(d){
          html+="<tr class='details-row'>"
          html+='<td>'+d.name+'</td>';
          html+='<td>'+d.brand.name+'</td>';
          html+='<td>'+d.model.name+'</td>';
          html+='<td id="part_id_row">'+d.part_id+'</td>';
          html+='<td>'+d.color+'</td>';
          html+='<td>'+d.sale_price+'</td></tr>';
        })
        $('.item-details tbody').html(html)
        $('.item-details').css('visibility','visible')
        $('#item-details-table').focus();
     })
  }
  
  $(document).on('click','.details-row',function(d){
    part_id=$(this).children().next().next().next('#part_id_row').text();
    console.log(part_id);
    $('#part_id').html("<option value='"+part_id+"'>"+part_id+"<option>")
    $('#part_id').trigger('select2:select');
    $('.item-details').css('visibility','hidden');
    $('#addnewbtn').attr('disabled',false);
    initDetailsStatus=false;
    $('#quantity').focus();
    getMultiply();
  })
  function courierSelection()
  {
    customer=$('#customer').val();
    if(customer!=null){
       axios.get("{{URL::to('admin/courier-top')}}/"+customer)
       .then(res=>{
        if(res.data.length>0){
          $('#courier').html("<option value='"+res.data.id+"'>"+res.data.name+'('+res.data.adress+")</option>")
        }
       })
    }
  }
  
  $(document).on('select2:unselect','#product', function(e){
    // $('#brand').val(null).trigger('change')
    // $('#model').val(null).trigger('change')
    $('#b_rate').val('');
    $('#part_id').val(null).trigger('change')
    singleCalc();
  })
  $(document).on('select2:select','#brand,#model',function(){
    if(initDetailsStatus==true){
      initDetails();
    }
  })
  $(document).on('select2:select','#customer,#product',function(){
    getMultiply();
  })
  function getMultiply(){
    customer=$('#customer').val();  
    console.log(customer)
    if(customer!=null){
      axios.get("{{URL::to('admin/get-customer-multiply')}}/"+customer)
      .then(res=>{
        $('#mltp').val(res.data.multiply);
      })
    }
  }
  $('#search_key').keyup(function(){  
      search_table($(this).val());  
  });
  function search_table(value){
    console.log(value)
      $('#product_added tr').each(function(){  
            var found = 'false';
            $(this).each(function(){  
                if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0)  
                {  
                      found = 'true';  
                }  
            });  
            if(found == 'true')  
            {  
                $(this).css('background','red');  
            }  
            else  
            {  
                $(this).css('background','white');
            }
      });
  } 
  var invoices= <?php echo trim(json_encode($invoice),'[]') ?>;
  console.log(invoices);
  $(document).ready(function(){
      showInvoice(invoices);
  })
    function showInvoice(invoices){
        $('#sale_type').val(invoices.sale_type)
        if(invoices.customer!=null){
          $('#customer').html('<option value="'+invoices.customer.id+'">'+invoices.customer.name+'</option>');
        }
        $('#hand_bill').val(invoices.hand_bill);
        $('#date').val(dateFormat(invoices.dates*1000))
        $('#discount').val(invoices.discount);
        $('#vat').val(invoices.vat);
        $('#transport').val(invoices.transport);
        $('#staff_note').val(invoices.staff_note);
        if(invoices.notes!=null){
          $('#note').html('<option value="'+invoices.notes.id+'">'+invoices.notes.note+'</option>')
        }
        $('#total').val(invoices.total);
        $('#total-item').val(invoices.total_item);
        total_item=parseInt(invoices.total_item)
        if(invoices.sale_type==2){
          console.log(parseInt(invoices.customer.phone))
          $('#w_mobile').val('5454512121');
          $('#w_name').val(invoices.customer.name);
          $('#w_adress').val(invoices.customer.adress);
        }
        if(invoices.sale_by==2){
          $('#shipping_name').val(invoices.shipping_customer.name);
          $('#shipping_mobile').val(invoices.shipping_customer.phone);
          $('#shipping_adress').val(invoices.shipping_customer.adress);
          $('#condition_amount').val(invoices.cond_amount);
        }
        if(invoices.courier!=null){
          $('#courier').html('<option value="'+invoices.courier.id+'">'+invoices.courier.name+'</option>')
        }
        
        $("input[name='sale_by[]'][value='"+invoices.sale_by+"']").attr('checked',true);
        customerVisibility();
        saleByCheck()
        // res=[];
        html="";
        for(i=0;i<invoices.sales.length;i++){
            console.log(invoices.sales[i]);
            d=invoices.sales[i];
            // console.log(invoices.sales[0]);
            // console.log(d);
            
            html+='<tr><td><input type="hidden" name="record_type[]" value="sales" /><input type="hidden" name="sale_id[]" value="'+d.id+'"" /><select class="form-control product" name="product[]"><option value="'+d.product_id+'">'+d.product.name+'</option></select></td>';
            html+=`<td><input type="number" disabled class="form-control bg-secondary text-light" name="stock[]" placeholder='0.00'/></td>`;
            html+=`<td><input type="number" class="form-control qantity" name="qantity[]" placeholder='0.00' value='`+d.deb_qantity+`'/></td>`;
            html+=`<td><input type="number" class="form-control price" name="price[]" placeholder='0.00'  value='`+d.price+`'/></td>`;
            html+=`<td><input type="number" class="form-control total" name="total[]" placeholder='0.00'/></td>`;
            html+=`<td><button class="btn btn-sm btn-danger removeItem" >X</button></td></tr>`;
        }
        $("#item_table_body").html(html);
        initSelect2();
        calculation();
    }
  </script>
    