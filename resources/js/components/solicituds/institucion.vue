<template>       

    <b-container>

        <b-row class="form-row mb-4">
            <b-col cols="4">
                <label for="nombre">Nombre</label><br>
                <b-form-input class="form-control" name="nombre" v-model="nombre" required list="list-nombre" @change="autocomplete()"></b-form-input>

                <datalist id="list-nombre">
                    <option v-for="item in items">{{ item }}</option>
                </datalist>
            </b-col>

            <b-col cols="4">
                <label for="telefono">Telefono</label><br>
                <input type="text" class="form-control" name="telefono" v-model="telefono" id="telefono" maxlength="255">
            </b-col>

            <b-col cols="4">
                <label for="direccion">Direccion</label><br>
                <input type="text" class="form-control" v-model="direccion" name="direccion" id="direccion" maxlength="255" required >            
            </b-col>
        </b-row>

    </b-container>
    
</template>

<script>
  export default {
    props: {
        instituciones:{
            type: Array,
            required: true,
            default: () => []
        },
    },
    data() {
      return {
          nombre: "",
          direccion: "",
          telefono: "",  
        items: [],
      }
    },
    mounted() {
        this.instituciones.forEach(element => {
            this.items.push(element.nombre);
        });
    },
    destroyed() {

    },
    methods: {
        autocomplete() {
            this.instituciones.forEach(element => {
                if (this.nombre == element.nombre) {
                    this.telefono = element.telefono;
                    this.direccion = element.direccion;
                }
            });
        }
    }
  }
</script>