<template>       

    <b-container>

        <b-row class="form-row mb-4">
            <b-col cols="4">
                <label for="ci">Cedula</label><br>
                <b-form-input class="form-control" name="cib" v-model="ci" required list="list-ci" @change="autocomplete()"></b-form-input>

                <datalist id="list-ci">
                    <option v-for="item in items">{{ item }}</option>
                </datalist>
            </b-col>

            <b-col cols="4">
                <label for="nombre">Nombre </label>
                <input type="text" class="form-control" v-model="nombre" name="nombreb" id="nombre" maxlength="255" required >
            </b-col>

            <b-col cols="4">
                <label for="apellido">Apellido</label><br>
                <input type="text" class="form-control" v-model="apellido" name="apellidob" id="apellido" maxlength="255" required >            
            </b-col>
        </b-row>
        
        <b-row class="form-row mb-4">
            <b-col cols="4">
                <label for="telefono">Telefono</label><br>
                <input type="text" class="form-control" name="telefonob" v-model="telefono" id="telefono" maxlength="255">
            </b-col>

            <b-col cols="4">
                <label for="parroquiab">Parroquia </label>
                <b-form-select v-model="parroquia" class="form-control" name="parroquiab" id="parroquiab" required>
                    <b-form-select-option value="villa de cura">Villa de Cura</b-form-select-option>
                    <b-form-select-option value="augusto mijares">Augusto Mijares</b-form-select-option>
                    <b-form-select-option value="magdaleno">Magdaleno</b-form-select-option>
                    <b-form-select-option value="san francisco de asis">San Francisco de Asis</b-form-select-option>
                    <b-form-select-option value="valles de tucutunemo">Valles de Tucutunemo</b-form-select-option>
                </b-form-select>
            </b-col>

            <b-col cols="4">
                <label for="sector">Sector</label><br>
                <input type="text" class="form-control" v-model="sector" name="sectorb" id="sector" maxlength="255" required >            
            </b-col>
        </b-row>

    </b-container>
    
</template>

<script>
  export default {
    props: {
        ciudadanos:{
            type: Array,
            required: true,
            default: () => []
        },
    },
    data() {
      return {
          ci: "",
          nombre: "",
          apellido: "",
          telefono: "",  
          direccion: "",  
          sector: "",
          parroquia: "",
        items: [],
      }
    },
    mounted() {
        this.ciudadanos.forEach(element => {
            this.items.push(element.ci);
        });
    },
    destroyed() {

    },
    methods: {
        autocomplete() {
            this.ciudadanos.forEach(element => {
                if (this.ci == element.ci) {
                    this.nombre = element.nombre;
                    this.apellido = element.apellido;
                    this.telefono = element.telefono;
                    this.parroquia = element.parroquia;
                    this.sector = element.sector;
                }
            });
        }
    }
  }
</script>