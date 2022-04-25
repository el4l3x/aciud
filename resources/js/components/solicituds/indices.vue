<template>
  <div>
    <highcharts class="hc" :options="chartOptions" ref="chart"></highcharts>
  </div>
</template>

<script>

export default {
    props: {
        data:{
            type: Object,
            required: true,
            default: () => []
        },
    },
    data() {
        return {
            chartOptions: {
                chart: {
                    renderTo: 'container',
                    type: 'pie'
                },
                title: {
                    text: 'Solicitudes'
                },
                series: [{
                    colorByPoint: true,
                    name: 'Total:',
                    data: [ 
                        {
                            name: 'Reclamos',
                            y: this.data.reclamos
                        }, 
                        {
                            name: 'Peticiones',
                            y: this.data.peticiones
                        }, 
                        {
                            name: 'Denuncias',
                            y: this.data.denuncias
                        }, 
                    ]
                }]
            }
        };
    },
     mounted() {
        //console.log(this.data);
    },
};
</script>