<script>
    var datatable;
    var removeArr=[];
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('make-price.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'customer',
            name:'customer',
          },
          {
            data:'product',
            name:'product',
          },
          {
            data:'price',
            name:'price',
          },
          {
            data:'action',
            name:'action',
          }
        ]
    });
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
    $('input,select').removeClass('is-invalid');
    product=$("select[name='product[]']").map(function(){
        return ($(this).val()==null? '': $(this).val());
    }).get();
    price=$("input[name='price[]']").map(function(){
        return $(this).val();
    }).get();
    console.log(product,price);
    let customer=$('#customer').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('customer',customer);
    formData.append('product',product);
    formData.append('price',price);
    formData.append('remove',removeArr);
    $('#exampleModalLabel').text('Add New Price');
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('make-price.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                remove();
                $('#modal').modal('hide');
                removeArr=[];
            }else if(response.data.error){
              var keys=Object.keys(response.data.error);
              errors="";
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);

                  errors+=response.data.error[d][0]+"<br>"
                  Swal.fire({
                    title: 'Opps !',
                    html: errors,
                    icon: 'warning',
                    showCancelButton: true,
                    showConfirmButton: false,
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
                // end sweetalert

              })
            }
        })
    }else{
      axios.post("{{URL::to('admin/make-price/')}}/"+id,formData)
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
$(document).delegate("#modalBtn", "click", function(event){
    clear();
    $('#exampleModalLabel').text('Add New Payment');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Supplier');
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
$(".product").select2({
  theme:'bootstrap4',
  placeholder:'Product',
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
});

}
var unique_number=0;
function addItem(){
  let html="<tr>";
      html+="<td><select class='form-control product' name='product[]'></select></td>";
      html+="<td><input class='form-control price' name='price[]' placeholder='0.00'></td>";
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
      cache:false,
    }
  });
  unique_number=unique_number+1;
}

$(document).on('click','.remove', function(e){
  e.preventDefault();
 console.log($(this).val());
 removeArr.push($(this).parent().prev().prev().children().val())
 $(this).parent().parent().remove();
 calculation();
 console.log(removeArr);
})
$(".customer").select2({
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
  $(".category").select2({
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
  });
// function paymentMethod(){
//     let method_type=$('#method').val()
//     console.log(method_type)
//     $('#cheque_no').val('');
//     $('#cheque_photo').val('');
//     $('#bank').val(null).trigger('change');
//     if(method_type==1){
//       $('.bank').removeClass('d-none');
//     }else{
//       $('.bank').addClass('d-none');
//     }
//   }

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

  function Apply(){
    let customer=$('#search_customer').val();
    let category=$('#category').val();

    axios.post("{{URL::to('/admin/customer-price')}}",{customer:customer,category:category})
    .then((res)=>{
      console.log(res)
      html="";
      res.data.forEach(function(d){
        console.log(d);
          html+="<tr><td>"+d.update_date+"</td>"
          html+="<td>"+d.product_code+"</td>"
          html+="<td>"+d.name+"</td>"
          html+="<td>"+d.set_price+"</td>"
          html+="<td>"+d.last_price+"</td>"
          html+="<td>"+d.sale_price+"</td></tr>"
      })
      $('#data-load').html(html);
    })
  }
 function getSetPrice(){
    customer=$('#customer').val();
    axios.get("{{URL::to('admin/price-list')}}/"+customer)
    .then((res)=>{
      console.log(res);
      let html="<tr>";
      res.data.forEach(function(d){
        html+="<td><select class='form-control product' name='product[]'><option value='"+d.product_id+"'>"+d.product.name+"</option></select></td>";
        html+="<td><input class='form-control price' name='price[]' placeholder='0.00' value="+d.price+"></td>";
        html+="<td><button class='btn btn-danger btn-sm remove'>X</button></td></tr>";
      })
      console.log(html)
      $('#payment-body').html(html)
      initSelect2();
    })
 }
</script>