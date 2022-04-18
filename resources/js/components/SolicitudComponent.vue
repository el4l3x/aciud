<template>
  <b-container fluid>
    <!-- User Interface controls -->
    <b-row>

      <b-col sm="4" md="5" class="my-1">

        <b-form-group
          label="Buscar"
          label-for="filter-input"
          label-cols-sm="3"
          label-align-sm="left"
          label-size="sm"
          class="mb-0"
        >
          <b-input-group size="sm">
            <b-form-input
              id="filter-input"
              v-model="filter"
              type="search"
              placeholder="..."
            ></b-form-input>

            <b-input-group-append>
              <b-button :disabled="!filter" @click="filter = ''">Limpiar</b-button>
            </b-input-group-append>
          </b-input-group>
        </b-form-group>

      </b-col>

      <b-col sm="8" lg="7" class="my-1">

        <b-form-group
          label="Filtrar por:"
          label-cols-sm="5"
          label-cols-md="4"
          label-cols-lg="3"
          label-align-sm="right"
          label-size="sm"
          class="mb-0"
        >

          <b-form-checkbox-group
            v-model="filterOn"
            class="mt-1"
          >
            <b-form-checkbox value="organismo">Organismo</b-form-checkbox>
            <b-form-checkbox value="tipo">Tipo</b-form-checkbox>
            <b-form-checkbox value="status">Status</b-form-checkbox>
          </b-form-checkbox-group>

        </b-form-group>
                
      </b-col>

    </b-row>

    <!-- Main table element -->
    <b-table
      :items="solicitudes"
      :fields="fields"
      :current-page="currentPage"
      :per-page="perPage"
      :filter="filter"
      :filter-included-fields="filterOn"
      :sort-by.sync="sortBy"
      :sort-desc.sync="sortDesc"
      :sort-direction="sortDirection"
      stacked="md"
      show-empty
      small
      @filtered="onFiltered"
      empty-filtered-text="No se consiguieron coincidencias"
      empty-text="No hay datos para mostrar"
    >

      <template #cell(actions)="row">
        <b-button v-b-tooltip.hover title="Eliminar" size="sm" @click="deletemodal(row.item, row.index, $event.target)" class="mr-1" variant="danger">
          <b-icon-trash></b-icon-trash>
        </b-button>
        <b-button v-b-tooltip.hover title="Ver Detalles" size="sm" @click="deletemodal(row.item, row.index, $event.target)" class="mr-1" variant="secondary">
          <b-icon-search></b-icon-search>
        </b-button>
        <b-button v-b-tooltip.hover title="Editar" size="sm" @click="deletemodal(row.item, row.index, $event.target)" class="mr-1" variant="info">
          <b-icon-pencil-fill></b-icon-pencil-fill>
        </b-button>
      </template>

    </b-table>

    <b-row>

      <b-col sm="2" md="3" class="my-1">
        <b-form-group
          label="Mostrar"
          label-for="per-page-select"
          label-cols-sm="8"
          label-cols-md="6"
          label-cols-lg="5"
          label-align-sm="left"
          label-size="sm"
          class="mb-0"
        >
          <b-form-select
            id="per-page-select"
            v-model="perPage"
            :options="pageOptions"
            size="sm"
          ></b-form-select>
        </b-form-group>
      </b-col>

      <b-col sm="10" md="9" class="my-1">
        <b-pagination
          v-model="currentPage"
          :total-rows="totalRows"
          :per-page="perPage"
          align="fill"
          size="sm"
          class="my-0"
        ></b-pagination>
      </b-col>
      
    </b-row>

    <!-- delete modal -->
    <b-modal :id="deleteModal.id" :title="deleteModal.title" @hide="resetDeleteModal" @ok="deleteOk(deleteModal.idDelete)">
      <template #default="{  }">
        <p>{{ deleteModal.content }}</p>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok(deleteModal.idDelete)">
            Borrar
        </b-button>
        <b-button size="sm" @click="cancel()">
            Cancelar
        </b-button>
      </template>
    </b-modal>

  </b-container>
</template>

<script>
  export default {
    props: {
      solicitudes:{
        type: Array,
        required: true,
        default: () => []
      },
    },
    data() {
      return {
        filterOn: [],
        datatab: [],
        fields: [
          {
            key: 'ciudadano',
            formatter: 'nombreCompleto',
            label: 'Solicitante',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'tipo',
            label: 'Tipo',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center text-capitalize',
          },
          {
            key: 'organismo.nombre',
            label: 'Organismo',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'status',
            label: 'Estado',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center text-capitalize',
          },
          {
            key: 'created_at',
            formatter: (value, key, item) => {
              return new Date(value).toLocaleDateString()
            },
            label: 'Fecha',
            sortable: true,
            thClass: 'text-center negrita',
            tdClass: 'small text-center',
          },
          {
            key: 'actions', label: 'Acciones'
          }
        ],
        totalRows: 1,
        currentPage: 1,
        perPage: 10,
        pageOptions: [10, 20, 30, { value: 100, text: "Mostrar Todo" }],
        sortBy: '',
        sortDesc: false,
        sortDirection: 'asc',
        filter: null,
        filterOn: [],
        deleteModal: {
          id: 'delete-modal',
          title: '',
          content: '',
          idDelete: ''
        },
      }
    },
    computed: {
      sortOptions() {
        // Create an options list from our fields
        return this.fields
          .filter(f => f.sortable)
          .map(f => {
            return { text: f.label, value: f.key }
          })
      }
    },
    mounted() {
      // Set the initial number of items
      this.totalRows = this.solicitudes.length;
      // Asignar array para data
      this.datatab = this.solicitudes;
    },
    methods: {
      deleteOk(id){
        axios.post('/solicitudes/'+id, {
          _method: 'delete'
        })
        .then(response=>{
            window.location = "/";
        }).catch(e => {
          console.log(e.response)
        });
      },
      nombreCompleto(value) {
        return `${value.nombre} ${value.apellido}`
      },
      formatoFecha(value) {
        return new Date(Value);
      },
      deletemodal(item, index, button) {
        this.deleteModal.title = `Borrar: ${item.product_name}`
        this.deleteModal.idDelete = item.id
        this.deleteModal.content = `Esta seguro que desea borrar la solicitud: ${item.codigo}??`
        this.$root.$emit('bv::show::modal', this.deleteModal.id, button)
      },
      resetDeleteModal() {
        this.deleteModal.title = ''
        this.deleteModal.content = ''
        this.deleteModal.idDelete = ''
      },
      onFiltered(filteredItems) {
        // Trigger pagination to update the number of buttons/pages due to filtering
        this.totalRows = filteredItems.length
        this.currentPage = 1
      },
      changeData(checked) {
        console.log(checked);
      }
    }
  }
</script>
