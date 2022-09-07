<script>
    var datatable;
    $(document).ready(function(){
        datatable= $('#datatable').DataTable({
        processing:true,
        serverSide:true,
        responsive:true,
        ajax:{
          url:"{{route('journal.index')}}"
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
  })
    

window.formRequest= function(event){
    $('.submit').attr('disabled',true);
    event.preventDefault();
    ledger=$("select[name='ledger[]']").map(function(){
        return $(this).val();
    }).get();
    subledger=$("select[name='subledger[]']").map(function(){
        console.log($(this).val()+'subledger');
        return ($(this).val()==null ? 'null' : $(this).val());
    }).get();
    debit=$("input[name='debit[]']").map(function(){
        return $(this).val();
    }).get();
    credit=$("input[name='credit[]']").map(function(){
        return $(this).val();
    }).get();
    comment=$("input[name='comment[]']").map(function(){
        return $(this).val();
    }).get();
    console.log(ledger,subledger,debit,credit,comment);
    $('input,select').removeClass('is-invalid');
    let date=$('#date').val();
    let note=$('#note').val();
    let id=$('#id').val();
    let formData= new FormData();
    formData.append('ledger',ledger);
    formData.append('subledger',subledger);
    formData.append('debit',debit);
    formData.append('credit',credit);
    formData.append('comment',comment);
    formData.append('date',date);
    formData.append('note',note);
    $('#exampleModalLabel').text('Add New Account Journal');
    console.log(id)
    if(id!=''){
      formData.append('_method','PUT');
    }
    //axios post request
    if (id==''){
         axios.post("{{route('journal.store')}}",formData)
        .then(function (response){
            if(response.data.message){

                toastr.success(response.data.message)
                datatable.ajax.reload();
                clear();
                Clean();
                $('#modal').modal('hide');
                $('.submit').attr('disabled',false);
            }else if(response.data.error){
              $('.submit').attr('disabled',false);
              var keys=Object.keys(response.data.error);
              let err="";
              keys.forEach(function(d){
                $('#'+d).addClass('is-invalid');
                $('#'+d+'_msg').text(response.data.error[d][0]);
                err+=response.data.error[d][0]+'\n';
              })
              Swal.fire({
                  title: 'Opps !',
                  text: err,
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                })
            }
        })
    }else{
      axios.post("{{URL::to('admin/accounts/journal/')}}/"+id,formData)
        .then(function (response){
          if(response.data.message){
              toastr.success(response.data.message);
              datatable.ajax.reload();
              clear();
              Clean();
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
function formRequestTry(){
  let date=$('#date').val();
  let amount=$('#total').text();
  Swal.fire({
      title: 'Are you sure?',
      html: "<p> Date: <b class='text-danger'>"+date+"</b></p><p>You Want Save this ?</p>",
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
    $('#exampleModalLabel').text('Add New Account Journal');

});
$(document).delegate(".editRow", "click", function(){
    $('#exampleModalLabel').text('Edit Account Journal');
    let route=$(this).data('url');
    axios.get(route)
    .then((data)=>{
      var editKeys=Object.keys(data.data);
      editKeys.forEach(function(key){
        if(key=='name'){
          $('#'+'name').val(data.data[key]);
        }
        if(key=='category_id'){
          $('#category').val(data.data[key]).niceSelect('update');
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
function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
  $('input').val('');
}
var unique_number=0;
function addItem(){
  let html="<tr>";
      html+="<td><select class='form-control ledger' name='ledger[]'></select></td>";
      html+="<td><select class='form-control subledger' name='subledger[]' id='subledger"+unique_number+"'></select></td>";
      html+="<td><input class='form-control debit' name='debit[]' placeholder='0.00'></td>";
      html+="<td><input class='form-control credit' name='credit[]' placeholder='0.00'></td>";
      html+="<td><input class='form-control comment' name='comment[]' placeholder='write comment'></td>";
      html+="<td><button class='btn btn-danger btn-sm remove'>X</button></td></tr>";
      $('#journal-body').append(html);
      initSelect2();
    
    $("#subledger"+unique_number).select2({
    theme:'bootstrap4',
    placeholder:'Ledger',
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

$('#date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
});
$(document).on('click','.remove',function(event){
  event.preventDefault();
  console.log(this)
  $(this).parent().parent().remove();
  // console.log()
  calculation()
})

$(document).on('focus keyup focusout','.debit',function(){
  value=$(this).val();
  if(value!=''){
    $(this).parent().next().children().attr('disabled',true);
  }else{
    $(this).parent().next().children().attr('disabled',false);
  }
  calculation()
})
$(document).on('focus keyup focusout','.credit',function(){
  value=$(this).val();
  if(value!=''){
    $(this).parent().prev().children().attr('disabled',true);
  }else{
    $(this).parent().prev().children().attr('disabled',false);
  }
  calculation()
})

function calculation(){
   debit=$("input[name='debit[]']").map(function(){
      return $(this).val();
   }).get();
   credit=$("input[name='credit[]']").map(function(){
      return $(this).val();
   }).get();
   totaldeb=debit.reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
   totalcred=credit.reduce((a,b)=>parseFloat(a||0)+parseFloat(b||0),0);
   console.log(totaldeb,totalcred);
   $('#total-debit').text(totaldeb.toFixed(2))
   $('#total-credit').text(totalcred.toFixed(2))
   if(totaldeb!=totalcred){
     $('#submitBtn').attr('disabled',true)
   }else{
     $('#submitBtn').attr('disabled',false)
   }
}

function Clean(){
  $('#journal-body').remove();
  $('#date').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
  calculation()
}
$(document).on('select2:unselect','.ledger', function (e) {
    console.log($(this));
    $(this).parent().next().children().val(null).trigger('change');
    console.log($(this).parent().next().children().val());
  });
</script>
