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


$(document).on('click','.remove', function(e){
  e.preventDefault();
 console.log($(this).val());
 removeArr.push($(this).parent().prev().prev().children().val())
 $(this).parent().parent().remove();
 calculation();
 console.log(removeArr);
})



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
  $("#category").select2({
    theme:'bootstrap4',
    placeholder:'select',
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
  $("#product").select2({
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
        return {
          results:response,
        }
      },
      cache:true,
    }
  })
  $("#supplier").select2({
    theme:'bootstrap4',
    placeholder:'select',
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
  $("#supplier").select2({
    theme:'bootstrap4',
    placeholder:'select',
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
  function Apply(){
    let fromDate=$('#fromDate').val();
    let toDate=$('#toDate').val();
    let category=$('#category').val();
    let product=$('#product').val();
    let supplier=$('#supplier').val();
   
    axios.post("{{URL::to('/admin/supplier-wise-purchase-report')}}",{from_date:fromDate,to_date:toDate,category:category,product:product,supplier:supplier})
    .then((res)=>{
      console.log(res)
      html="";
      res.data.forEach(function(d){
        console.log(d);
          html+="<tr>"
          html+="<td class='text-left'>"+d.name+"</td>";
          html+="<td class='text-right'>"+d.qantity+' '+d.unit_name+"</td>";
          html+="<td class='text-right'>"+((parseFloat(d.price))).toFixed(2)+"</td></tr>";  
      })
      $('#data-load').html(html);
      $('#printed_user').text("{{auth()->user()->name}}");
      showPrintTime();
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

  function dateFormatSalesInvoice(data){
    date=new Date(data);

    let dates = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    let year = date.getFullYear();
    return(((year).toString()).substring(2,4)+month+dates);
    // return date;
  }

  $('#fromDate,#toDate').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });

  function showPrintTime(){
    time=moment().format('MMMM Do YYYY, h:mm:ss a');
    $('#print_time').text(time);
  }
</script>