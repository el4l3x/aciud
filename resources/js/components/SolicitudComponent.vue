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
      
      <b-col sm="6" md="5" class="my-1">

      </b-col>

      <b-col sm="2" lg="2" class="my-1">

          <b-button variant="outline-info" v-b-modal.filtros>Filtros</b-button>  
                
      </b-col>

    </b-row>

    <!-- Main table element -->
    <b-table
      id="solicituds-table"
      :items="data"
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
        <b-button :href="urlShow(row.item)" target="blank" v-b-tooltip.hover title="Ver Detalles" size="sm" class="mr-1" variant="secondary">
          <b-icon-search></b-icon-search>
        </b-button>
        <b-button v-if="rol == 2" :href="urlEdit(row.item)" v-b-tooltip.hover title="Editar" size="sm" class="mr-1" variant="info">
          <b-icon-pencil-fill></b-icon-pencil-fill>
        </b-button>
        <b-button v-b-tooltip.hover title="Eliminar" size="sm" @click="deletemodal(row.item, row.index, $event.target)" class="mr-1" variant="danger">
          <b-icon-trash></b-icon-trash>
        </b-button>
        <b-button v-if="rol != 1" v-b-tooltip.hover title="Cambiar Status" size="sm" @click="statusmodal(row.item, row.index, $event.target)" class="mr-1" variant="info">
          <b-icon-exclamation-circle></b-icon-exclamation-circle>
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
    
    <!-- status modal -->
    <b-modal :id="statusModal.id" :title="statusModal.title" @hide="resetStatusModal" @ok="statusOk(statusModal.idStatus)">
      <template #default="{  }">
        <b-form-select v-model="status" class="mb-3">
          <b-form-select-option value="pendiente">Pendiente</b-form-select-option>
          <b-form-select-option value="en proceso">En Proceso</b-form-select-option>
          <b-form-select-option value="realizado">Realizado</b-form-select-option>
          <b-form-select-option value="en espera de">En Espera De</b-form-select-option>
        </b-form-select>
      </template>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok(statusModal.idStatus)">
            Cambiar
        </b-button>
        <b-button size="sm" @click="cancel()">
            Cancelar
        </b-button>
      </template>
    </b-modal>

    <!-- filtros modal -->
    <b-modal id="filtros" title="Filtros" @ok="filtroOk()">
      <b-form-select v-model="filtroStatus" class="mb-3" multiple>
        <b-form-select-option :value="null">Por Status</b-form-select-option>
        <b-form-select-option value="pendiente">Pendiente</b-form-select-option>
        <b-form-select-option value="realizado">Realizado</b-form-select-option>
        <b-form-select-option value="en proceso">En Proceso</b-form-select-option>
        <b-form-select-option value="en espera de">En espera de</b-form-select-option>
      </b-form-select>
      
      <b-form-select v-model="filtroTipo" class="mb-3" multiple>
        <b-form-select-option :value="null">Por Tipo</b-form-select-option>
        <b-form-select-option value="peticion">Peticion</b-form-select-option>
        <b-form-select-option value="reclamo">Reclamo</b-form-select-option>
        <b-form-select-option value="denuncia">Denuncia</b-form-select-option>
      </b-form-select>
      
      <b-form-select v-model="filtroOrganismo" class="mb-3" multiple>
        <b-form-select-option :value="null">Por Tipo</b-form-select-option>
        <b-form-select-option :value="1">Coordinación de tecnología e informática</b-form-select-option>
        <b-form-select-option :value="2">Dirección de desarrollo social</b-form-select-option>
        <b-form-select-option :value="3">Dirección de Ingeniería Municipal</b-form-select-option>
        <b-form-select-option :value="4">Dirección de servicios Públicos municipales</b-form-select-option>
        <b-form-select-option :value="5">Dirección de Catastro y Ejido</b-form-select-option>
        <b-form-select-option :value="6">Instituto autónomo de la policía municipal</b-form-select-option>
        <b-form-select-option :value="7">Protección civil</b-form-select-option>
        <b-form-select-option :value="8">Protección del niño</b-form-select-option>
        <b-form-select-option :value="9">Registro civil</b-form-select-option>
        <b-form-select-option :value="10">Instituto para la mujer</b-form-select-option>
        <b-form-select-option :value="11">Instituto municipal para la vivienda</b-form-select-option>
      </b-form-select>
      <template #modal-footer="{ ok, cancel }">
        <b-button size="sm" variant="primary" @click="ok()">
            Filtrar
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
      rol:{
        type: Number,
        required: true,
        default: () => 1
      },
    },
    data() {
      return {
        status: 'pendiente',
        filterOn: [],
        filterSecond: [],
        filtroStatus: [null],
        filtroTipo: [null],
        filtroOrganismo: [null],
        fields: [
          {
            key: 'beneficiarios',
            formatter: 'nombreCompleto',
            /*formatter: (value, key, item) => {
              if (item.institucion != null) {
                var idben = "1"
                var nombre = "b"
                item.involucrados.forEach(element => {
                  if (element.status == "beneficiario") {
                    idben = element.ciudadano_id
                  }
                });
                item.beneficiarios.forEach(element => {
                  if (element.id == idben) {
                    nombre = element.nombre
                  }
                });
                return nombre
              } else {                
                return item.institucion.nombre
              }
            },*/
            label: 'Beneficiario',
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
        statusModal: {
          id: 'status-modal',
          title: '',
          content: '',
          idStatus: ''
        },
        data: [],
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
      this.data = this.solicitudes;
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
      statusOk(id){
        axios.post('/solicitudes/status/'+id, {
          _method: 'post',
          status: this.status
        })
        .then(response=>{
            //console.log(response);
            window.location = "/";
        }).catch(e => {
          console.log(e.response)
        });
      },
      
      filtroOk(){
        axios.post('/solicitudes/filter', {
          _method: 'post',
          filtroStatus: this.filtroStatus,
          filtroTipo: this.filtroTipo,
          filtroOrganismo: this.filtroOrganismo
        })
        .then(response=>{
            this.data = response.data;
            this.$root.$emit('bv::refresh::table', 'solicituds-table')
            
            console.log(response.data);        
        }).catch(e => {
          console.log(e.response)
        });
      },
      nombreCompleto(value, key, item) {

        if (item.institucion == null) {
          var idben = "1"
          var nombre = "error"
          var involucrados = item.involucrados
          item.involucrados.forEach(element => {
            if (element.pivot.status == "beneficiario") {
              idben = element.id
              nombre = element.nombre+' '+element.apellido
            }
          });
          /*item.beneficiarios.forEach(element => {
            if (element.id == idben) {
              nombre = element.nombre+' '+element.apellido
            }
          });*/
          return nombre
        } else {                
          return item.institucion.nombre
        }
        return "holis"
      },
      deletemodal(item, index, button) {
        this.deleteModal.title = `Borrar Solicitud`
        this.deleteModal.idDelete = item.id
        this.deleteModal.content = `Esta seguro que desea borrar la solicitud: ${item.codigo}??`
        this.$root.$emit('bv::show::modal', this.deleteModal.id, button)
      },
      secondFilter(select) {
        console.log(this.filterSecond);
      },
      statusmodal(item, index, button) {
        this.statusModal.title = `Cambiar Status`
        this.statusModal.idStatus = item.id
        this.$root.$emit('bv::show::modal', this.statusModal.id, button)
      },
      urlShow(item) {
        var url = "/solicitudes/"+item.id;
        return url;
      },
      urlEdit(item) {
        var url = "/solicitudes/"+item.id+"/edit";
        return url;
      },
      resetDeleteModal() {
        this.deleteModal.title = ''
        this.deleteModal.content = ''
        this.deleteModal.idDelete = ''
      },
      resetStatusModal() {
        this.statusModal.title = ''
        this.statusModal.idStatus = ''
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
