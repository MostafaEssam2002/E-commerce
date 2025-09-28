<div style="width: 30%; height: 30%; background-color: black">
    <canvas id="mychart" > </canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    let products=@json($products);
    for(let i=0 ; i<products.length ; i++){
        console.log(products[i].name);
    }
    const data = {
        labels: products.map(p=>p.name),
        datasets: [
            {
            label: 'Dataset 1',
            data: products.map(p=>p.quantity),
            backgroundColor: ["Red", "Orange", "Yellow", "Green", "Blue", "Indigo", "Violet", "Black", "White", "Gray" ],
        },
    ]
    };
    const config = {
        type: 'doughnut',
        data: data,
        options:{
            plugins:{
                legend:{
                    display:false
                }
            }
        }
        };
new Chart(
        document.getElementById('mychart'),
        config
    );
</script>
