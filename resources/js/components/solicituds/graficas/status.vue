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
                    data: [{
                        name: 'Pendiente',
                        y: this.data.pendiente
                        }, {
                        name: 'En Proceso',
                        y: this.data.enproceso
                        }, {
                        name: 'Realizado',
                        y: this.data.realizado
                        }, {
                        name: 'En Espera De',
                        y: this.data.enesperade
                        }]
                }]
            }
        };
    },
     mounted() {

    },
};
</script>