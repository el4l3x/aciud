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
                    data: this.datachart
                }]
            }
        };
    },
     mounted() {
        console.log(this.data);
        
        switch (this.data.tipo) {
            case 'total':

                this.datachart = [
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
                ];
                
                break;
                
                case 'status':

                this.datachart = [
                    {
                        name: 'Pendiente',
                        y: this.data.pendiente
                    }, 
                    {
                        name: 'espera',
                        y: this.data.enesperade
                    }, 
                    {
                        name: 'proceso',
                        y: this.data.enproceso
                    }, 
                    {
                        name: 'Realizado',
                        y: this.data.realizado
                    }, 
                ];
                
                break;
        
            default:

                this.datachart = [
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
                ];

                break;
        };
        console.log(this.datachart);

    },
};
</script>