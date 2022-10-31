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
    receivePaymentLineChart()
    axios.get(baseURL+"/admin/sales-yearly-bar-chart")
    .then(res=>{
        console.log(res)
        barChart(res.data)
    })
    axios.get(baseURL+"/admin/receive-payment-yearly-line-chart")
    .then(res=>{
        console.log(Object.keys(res.data.receive))
        labels=Object.keys(res.data.receive);
        rValue=Object.values(res.data.receive)
        pValue=Object.values(res.data.payment)
        receivePaymentLineChart(labels,rValue,pValue);
    })
})
function barChart(data)
{
    // console.log(data)
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

// line chart 
function receivePaymentLineChart(labels,rValue,pValue)
{
    var salesGraphChartCanvas=$('#line-chart').get(0).getContext('2d')
    var salesGraphChartData={
        labels:labels,
        datasets:[
            {
                label:'Receive',
                fill:false,
                borderWidth:2,
                lineTension:0,
                spanGaps:true,
                borderColor:'#00A650',
                pointRadius:3,
                pointHoverRadius:7,
                pointColor:'#efefef',
                pointBackgroundColor:'#efefef',
                data:rValue
            },
            {
                label:'Payment',
                fill:false,
                borderWidth:2,
                lineTension:0,
                spanGaps:true,
                borderColor:'#b4a7d6',
                pointRadius:3,
                pointHoverRadius:7,
                pointColor:'#8e7cc3',
                pointBackgroundColor:'#351c75',
                data:pValue
            }
        ]
    }
var salesGraphChartOptions={
    maintainAspectRatio:false,
    responsive:true,
    legend:{display:false},
    scales:
    {
        xAxes:[
            {
                ticks:{
                    fontColor:'#efefef'
                },
                gridLines:{
                    display:false,
                    color:'#efefef',
                    drawBorder:false
                }
            }
        ],
        yAxes:[
            {
                ticks:{stepSize:0,fontColor:'#909090'},
                gridLines:{
                    display:true,color:'#909090',
                    drawBorder:false
                }
            }
        ]
    }
}
    var salesGraphChart=new Chart(salesGraphChartCanvas,{
        type:'line',
        data:salesGraphChartData,
        options:salesGraphChartOptions
    })
}

  
</script>