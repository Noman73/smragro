<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('product.index')}}"
        },
        columns:[
          {
            data:'DT_RowIndex',
            name:'DT_RowIndex',
            orderable:false,
            searchable:false
          },
          {
            data:'category',
            name:'category',
          },
          {
            data:'name',
            name:'name',
          },
          {
            data:'image',
            name:'image',
          },
          {
            data:'status',
            name:'status',
          },
          {
            data:'action',
            name:'action',
          }
        ],
        'columnDefs': [
              {
                  "targets": 0, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
            {
                  "targets": 1, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
            {
                  "targets": 2, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
            {
                  "targets": 3, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
            {
                  "targets": 4, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
            {
                  "targets": 5, // your case first column
                  "className": "align-middle",
                  // "width": "4%"
            },
          ]

    });
  })
    

window.formRequest= function(){
    $('input,select').removeClass('is-invalid');
    let category=$('#category').val();
    let name=$('#name').val();
    let product_code=$('#product_code').val();
    let model_no=$('#model_no').val();
    let sale_price=$('#sale_price').val();
    let buy_price=$('#buy_price').val();
    let warranty=$('#warranty').val();
    let unit_type=$('#unit_type').val();
    let product_type=$('#product_type').val();
    let reorder_level=$('#reorder_level').val();
    let status=$('#status').val();
    let sale=($('#sale').prop('checked') ? 1 :0);
    let purchase=($('#purchase').prop('checked') ? 1 :0);
    let production=($('#production').prop('checked') ? 1 :0);
    let combo=($('#combobox').prop('checked') ? 1 :0);

    let products=$("select[name='products[]']").map(function(){
      return $(this).val();
    }).get();
    let qantity=$("input[name='qantity[]']").map(function(){
      return $(this).val();
    }).get();
    console.log(products);
    let file=document.getElementById('image').files;
    // return false;
    let id=$('#id').val();
    let formData= new FormData();
    if(unit_type==null){
      unit_type='';
    }
    if(category==null){
      category='';
    }
    formData.append('category',category);
    formData.append('name',name);
    formData.append('product_code',product_code);
    formData.append('model_no',model_no);
    formData.append('sale_price',sale_price);
    formData.append('buy_price',buy_price);
    formData.append('warranty',warranty);
    formData.append('unit_type',unit_type);
    formData.append('sale',sale);
    formData.append('purchase',purchase);
    formData.append('production',production);
    formData.append('combo',combo);
    formData.append('products',products);
    formData.append('qantity',qantity);
    formData.append('reorder_level',reorder_level);
    formData.append('status',status);
    if(file[0]!=undefined){
      formData.append('image',file[0]);
    }
    $('#exampleModalLabel').text('Add New Product');
    console.log(id)
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('product.store')}}",formData)
        .then(function (response){
            if(response.data.message){
                toastr.success(response.data.message)
                datatable.ajax.reload();
                clear();
                $('#modal').modal('hide');
                $('.submit').attr('disabled',false);
            }else if(response.data.error){
              $('.submit').attr('disabled',false);
              var keys=Object.keys(response.data.error);
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
              })
            }
        })
    }else{
      axios.post("{{URL::to('admin/product/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
              $('#modal').modal('hide');
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
    $('#exampleModalLabel').text('Add New Product');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Product');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      console.log(data)
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
        if(key!='image'){
          $('#'+key).val(data.data[key]);
        }
         $('#modal').modal('show');
         $('#id').val(data.data.id);
         if(key=='category'){
            $('#category').html("<option value='"+data.data.category.id+"'>"+data.data.category.name+"</option>");
         }
         if(key=='unit_id'){
            $('#unit_type').html("<option value='"+data.data[key]+"'>"+data.data.unit.name+"</option>");
         }
         if(key=='sale'){
          $('#sale').attr('checked',false);
            if(data.data[key]==1){
              $('#sale').attr('checked',true);
            }
         }
         if(key=='purchase'){
            $('#purchase').attr('checked',false);
            if(data.data[key]==1){
              $('#purchase').attr('checked',true);
            }
         }
         if(key=='production'){
          $('#production').attr('checked',false);

            if(data.data[key]==1){
              $('#production').attr('checked',true);
            }
         }
         if(key=='combo'){
          $('#combobox').attr('checked',false);
            if(data.data[key]==1){
              $('#combobox').attr('checked',true);
            }
         }
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
  $("#unit_type").select2({
    theme:'bootstrap4',
    placeholder:'select',
    allowClear:true,
    ajax:{
      url:"{{URL::to('/admin/get-unit')}}",
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

  function addItem(){
    html ="<tr>";
    html+="<td><select class='form-control product' name='products[]' ></select></td>";
    html+="<td><input class='form-control qantity' name='qantity[]' placeholder='0.00'></td>";
    html+="<td><button class='btn btn-sm btn-danger remove'>X</button></td>";
    html+="</tr>";
    $('#products').append(html);
    initSelect2()
  }
function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
}
$(document).on('click','.remove',function(){
   $(this).parent().parent().remove()
})


// function productType(){
//   type=$('#combobox').prop('checked');
//   if(type){
//     $('#product-table').removeClass('d-none');
//   }else{
//     $('#product-table').addClass('d-none');
//   }
// }
$('#combobox').change(function(){
  type=$(this).prop('checked');
  if(type){
    $('#product-table').removeClass('d-none');
  }else{
    $('#product-table').addClass('d-none');
  }
})

$(document).on('change','#category',function(){
  category_id=$(this).val();
  $('#product_code');
  axios.get("{{URL::to('admin/count_product/')}}/"+category_id)
  .then(response=>{
    console.log(response)
      p_code=$("#product_code").val();
      console.log(((category_id).padEnd(4,'0')).toString());
      $('#product_code').val(((category_id).padEnd(4,'0')).toString()+''+(parseInt(response.data)+1))
  })
})

$(document).on('select2:unselect','#category',function(){
  $('#product_code').val('')
})
</script>
