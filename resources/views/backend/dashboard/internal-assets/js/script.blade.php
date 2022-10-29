<script>
    function fetch()
    {
        from_date=$('#fromDate').val();
        to_date=$('#toDate').val();
        axios.post('admin/dashboard-data',{from_date:from_date,to_date:to_date})
        .then((res)=>{
            console.log(res);
            
            html='';
            bank_html='';
            res.data.top_product.forEach(function(d){
                html+="<tr>"
                html+="<td>"+d.name+"</td>"
                html+="<td>"+d.product_code+"</td>"
                html+="<td>"+(d.qantity==null ? '0.00' :d.qantity)+"</td></tr>"
            })
            res.data.bank.forEach(function(d){
                bank_html+="<tr>"
                bank_html+="<td>"+d.name+"</td>"
                bank_html+="<td>"+d.code+"</td>"
                bank_html+="<td>"+(d.balance==null ? '0.00' :d.balance)+"</td></tr>"
            })
            $('#top_product').html(html);
            $('#bank_table').html(bank_html);
            $('#customer').text(res.data.total_customer);
            $('#customer_balance').text(res.data.customer_balance);
            $('#supplier_balance').text(res.data.supplier_balance);
            $('#current_balance').text(res.data.current_balance);
            $('#total_bank').text(res.data.total_bank);
            $('#supplier').text(res.data.total_supplier);
            $('#total_sale_amount').text(res.data.total_sale_amount);
            $('#total_buy_amount').text(res.data.total_buy_amount);
        })
    }

    $(document).ready(function(){
        // fetch();
    })
    $(document).on('change','#fromDate,#toDate',function(){
        fetch();
    })
    $('#fromDate,#toDate').daterangepicker({
        showDropdowns: true,
        singleDatePicker: true,
        // parentEl: ".bd-example-modal-lg .modal-body",
        locale: {
            format: 'DD-MM-YYYY',
        }
  });
//sales bar chart 
$(document).ready(function(){
    axios.get(baseURL+"/admin/sales-yearly-bar-chart")
    .then(res=>{
        console.log(res)
        barChart(res.data)
    })
})
function barChart(data)
{
    var lebels=Object.keys(data);
    var values=Object.values(data);
    var ctx = document.getElementById('sales-chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: lebels,
            datasets: [{
                label: '# Total Sale',
                data: values,
                backgroundColor: [
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8',
                    '#8095e8'
                ],
                borderColor: [
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                    '#443826',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

  
</script>