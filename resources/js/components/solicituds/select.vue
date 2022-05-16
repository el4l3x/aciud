<template>

    <b-container>

        <b-row class="form-row mb-4">
            <b-col cols="4">
                <label for="ci">Cedula</label><br>
                <b-form-input class="form-control" name="ci" v-model="ci" required list="list-ci" @change="autocomplete()"></b-form-input>

                <datalist id="list-ci">
                    <option v-for="item in items">{{ item }}</option>
                </datalist>
            </b-col>

            <b-col cols="4">
                <label for="nombre">Nombre </label>
                <input type="text" class="form-control" v-model="nombre" name="nombre" id="nombre" maxlength="255" required >
            </b-col>

            <b-col cols="4">
                <label for="apellido">Apellido</label><br>
                <input type="text" class="form-control" v-model="apellido" name="apellido" id="apellido" maxlength="255" required >            
            </b-col>
        </b-row>
        
        <b-row class="form-row mb-4">
            <b-col cols="4">
                <label for="telefono">Telefono</label><br>
                <b-input-group>
                    <template #prepend>
                        <b-form-select v-model="codeph" class="mb-3 form-control">
                            <b-form-select-option value="0424" selected>0424</b-form-select-option>
                            <b-form-select-option value="0414">0414</b-form-select-option>
                            <b-form-select-option value="0412">0412</b-form-select-option>
                            <b-form-select-option value="0416">0416</b-form-select-option>
                            <b-form-select-option value="0426">0426</b-form-select-option>
                        </b-form-select>
                    </template>

                    <b-form-input type="text" class="" name="telefonob" v-model="telefono" id="telefono" maxlength="7"></b-form-input>
                    
                </b-input-group>
                <!--<input type="text" class="form-control" name="telefono" v-model="telefono" id="telefono" maxlength="255">-->
            </b-col>

            <b-col cols="4">
                <label for="parroquia">Parroquia </label>
                <b-form-select v-model="parroquia" class="form-control" name="parroquia" id="parroquia" required>
                    <b-form-select-option value="villa de cura">Villa de Cura</b-form-select-option>
                    <b-form-select-option value="augusto mijares">Augusto Mijares</b-form-select-option>
                    <b-form-select-option value="magdaleno">Magdaleno</b-form-select-option>
                    <b-form-select-option value="san francisco de asis">San Francisco de Asis</b-form-select-option>
                    <b-form-select-option value="valles de tucutunemo">Valles de Tucutunemo</b-form-select-option>
                </b-form-select>
            </b-col>

            <b-col cols="4">
                <label for="sector">Sector</label><br>
                <input type="text" class="form-control" v-model="sector" name="sector" id="sector" maxlength="255" required >            
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
        codeph: "0424",     
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
                    var code = element.telefono.split(' ');
                    this.telefono = code[1];
                    this.codeph = code[0];
                    this.parroquia = element.parroquia;
                    this.sector = element.sector;
                }
            });

        }
    }
  }
</script>