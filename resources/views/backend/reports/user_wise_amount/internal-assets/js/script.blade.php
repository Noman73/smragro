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




function clear(){
  $("input").removeClass('is-invalid').val('');
  $(".invalid-feedback").text('');
}




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

 


  function Apply(){
    let fromDate=$('#fromDate').val();
    let toDate=$('#toDate').val();
    axios.post("{{URL::to('admin/user-wise-amount')}}",{from_date:fromDate,to_date:toDate})
    .then((res)=>{
      console.log(res)
      html="";
      i=0;
      total=0;
      res.data.forEach(function(d){
        console.log(d);
          html+="<tr>"
          html+="<td class='text-left'>"+(i=i+1)+"</td>"
          html+="<td class='text-center'>#"+((d.id).toString()).padStart(5, '0')+'-'+d.name+"</td>"
          html+="<td class='text-center'>"+d.method+"</td>"
          html+="<td class='text-right'>"+d.debit+"</td>"
          html+="<td class='text-right'>"+d.credit+"</td>"
          html+="<td class='text-right'>"+d.total+"</td>"
          html+="</tr>";  
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
  function dateFormatInvId(data){
    date=new Date(data);
    let dates = ("0" + date.getDate()).slice(-2);
    let month = ("0" + (date.getMonth() + 1)).slice(-2);
    let year = date.getFullYear();
    return(dates+month+((year).toString()).substring(2,4));
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