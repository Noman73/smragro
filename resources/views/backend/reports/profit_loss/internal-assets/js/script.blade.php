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
  function Apply(){
    let fromDate=$('#fromDate').val();
    let toDate=$('#toDate').val();
    $('#inv_from_date').text(fromDate);
    $('#inv_to_date').text(toDate);
    axios.post("{{URL::to('/admin/profit_loss')}}",{from_date:fromDate,to_date:toDate})
    .then((res)=>{
      console.log(res)
      income=parseFloat(res.data.sales[0].total)+parseFloat(res.data.closing_stock[0].total);
      expence=parseFloat(res.data.opening_stock)+parseFloat(res.data.purchase[0].total);
      html="";
      html+="<tr>"
      html+="<td><table width='100%'>"
      html+="<tr><td class='font-weight-bold text-left'>Opening Stock</td><td class='float-right'>"+parseFloat(res.data.opening_stock).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Purchase Account</td><td class='float-right'>"+parseFloat(res.data.purchase[0].total).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Gross Profit</td><td class='float-right'>"+((parseFloat(res.data.sales[0].total)+parseFloat(res.data.closing_stock[0].total))-(parseFloat(res.data.purchase[0].total)+parseFloat(res.data.opening_stock))).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Indirect Expences</span></td></tr>"
      res.data.expenses.forEach(function(d){
        expence+=parseFloat(d.total);
      html+="<tr><td class=' text-left ml-2'>"+d.name+"</td><td class='float-right'>"+parseFloat(d.total).toFixed(2)+"</td></tr>";
      })
      html+="</td></table>";
      html+="<td><table width='100%'>"
      html+="<tr><td class='font-weight-bold text-left'>Sales Account</td><td class='text-right'>"+parseFloat(res.data.sales[0].total).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Closing Stock</td><td class='text-right'>"+parseFloat(res.data.closing_stock[0].total).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Gross Profit</td><td class='text-right'>"+((parseFloat(res.data.sales[0].total)+parseFloat(res.data.closing_stock[0].total))-(parseFloat(res.data.purchase[0].total)+parseFloat(res.data.opening_stock))).toFixed(2)+"</td></tr>";
      html+="<tr><td class='font-weight-bold text-left'>Indirect Income</td></tr>";
      res.data.indirect_income.forEach(function(d){
      income+=parseFloat(d.total);
      html+="<tr><td class='float-left ml-2'>"+d.name+"</td><td class='float-right'>"+parseFloat(d.total).toFixed(2)+"</td></tr>";
      })
      html+="</td>";
      html+="</tr></table>";
      html+="<tr><th  class='text-left'>Net Profit</th><th class='text-right'>"+(parseFloat(income)-parseFloat(expence)).toFixed(2)+"</th>";
      html+="</tr>";
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