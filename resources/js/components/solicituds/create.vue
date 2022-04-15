<template>
  <div>
        <b-input-group>
            <b-input-group-prepend>
                <b-form-checkbox switch class="mr-n2" :value="status" @click="status = !status"></b-form-checkbox>
            </b-input-group-prepend>
            <b-form-input :disabled="!status"></b-form-input>
        </b-input-group>

  </div>
</template>

<script>
  export default {
    data() {
      return {
        status: true
      }
    },
    mounted() {
      console.log(this.status)
    }
  }
</script>