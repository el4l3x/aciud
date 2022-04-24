<template>
    <b-row class="form-row mb-4">
        <b-col cols="4">
            <label for="ci">Cedula</label><br>
            <div class="autocomplete">
                <input v-model="search" @input="onChange" type="text" class="form-control" name="nombre" id="nombre" maxlength="255" required/>
                <ul v-show="isOpen" class="autocomplete-results" >
                    <li v-for="(result, i) in results" :key="i" @click="setResult(result)" class="autocomplete-result">
                        {{ result }}
                    </li>
                </ul>
            </div>
        </b-col>

        <b-col cols="4">
            <label for="nombre">Nombre </label>
            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="255" required >
        </b-col>

        <b-col cols="4">
            <label for="apellido">Apellido</label><br>
            <input type="text" class="form-control" name="apellido" id="apellido" maxlength="255" required >            
        </b-col>
    </b-row>
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
        items: [],
        search: '',
        results: [],
        isOpen: false,
      }
    },
    mounted() {
        document.addEventListener('click', this.handleClickOutside);
        this.ciudadanos.forEach(element => {
            this.items.push(element.ci);
        });
    },
    destroyed() {
        document.removeEventListener('click', this.handleClickOutside);
    },
    methods: {
        setResult(result) {
            this.search = result;
            this.isOpen = false;
        },
        onChange() {
            this.filterResults();
            this.isOpen = true;
        },
        filterResults() {
            var data = this.search;
            /^[a-zA-ZñÑáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ]+$/
            var regex = new RegExp(`ReGeX${data}ReGeX`);
            this.items.forEach(element => {
                if (regex.test(element.ci)) {                    
                    this.results = element.ci
                }
            });
            console.log(this.results);
        },
        handleClickOutside(event) {
            if (!this.$el.contains(event.target)) {
                this.isOpen = false;
            }
        }
    }
  }
</script>