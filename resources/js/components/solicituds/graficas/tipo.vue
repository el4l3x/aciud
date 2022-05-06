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
                    text: 'Solicitudes: '+this.data.solicitudes
                },
                series: [{
                    colorByPoint: true,
                    name: 'Total:',
                    //data: this.datachart
                    data: [{
                        name: 'Reclamos',
                        y: this.data.reclamos
                        }, {
                        name: 'Peticiones',
                        y: this.data.peticiones
                        }, {
                        name: 'Denuncias',
                        y: this.data.denuncias
                        }]
                }]
            }
        };
    },
     mounted() {

    },
};
</script>