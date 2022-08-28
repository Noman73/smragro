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

  function Apply(){
    let toDate=$('#toDate').val();
    $('#inv_to_date').text(toDate);
    $('.inv_ledger').removeClass('d-none')
    axios.post("{{URL::to('/admin/bank-balance-report')}}",{to_date:toDate})
    .then((res)=>{
        balance=0;
      console.log(res)
      html="";
      res.data.forEach(function(d){
        balance+=parseFloat(d.balance);
        console.log(d);
          html+="<tr>"
          html+="<td class='text-left'>"+d.name+"</td>";
          html+="<td class='text-right'>"+(parseFloat(d.balance).toFixed(2))+"</td>";  
          
      })
      html+="<tr>";
      html+="<th class='text-right'>Total</th>";
      html+="<th class='text-right'>"+balance.toFixed(2)+"</th>";

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