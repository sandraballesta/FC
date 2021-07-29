<template>
  <b-container>

     <!-- Filter controls -->
      <b-row>
        <b-col lg="6" class="my-1">
          <b-form-group
            label="Filter"
            label-for="filter-input"
            label-cols-sm="3"
            label-align-sm="right"
            label-size="sm"
            class="mb-0"
          >
            <b-input-group size="sm">
              <b-form-input
                id="filter-input"
                v-model="filter"
                type="search"
                placeholder="Type to Search"
              ></b-form-input>

              <b-input-group-append>
                <b-button :disabled="!filter" @click="filter = ''">Clear</b-button>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>

        <b-col lg="6" class="my-1">
          <div>
              <b-form-group label="Filtrar por:" v-slot="{ ariaDescribedby }">
                <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="selected" value="name">Estado del Pedido</b-form-radio>
                <b-form-radio v-model="selected" :aria-describedby="ariaDescribedby" name="selected" value="country_name">País</b-form-radio>
              </b-form-group>
            </div>
        </b-col> 
      

    <!-- Paginació -->
     
        <b-col sm="5" md="6" class="my-1">
          <b-form-group
            label="Mostrar resultados"
            label-for="per-page-select"
            label-cols-sm="6"
            label-cols-md="4"
            label-cols-lg="3"
            label-align-sm="right"
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

        <b-col sm="7" md="6" class="my-1">
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
  
   <!-- Table -->
    <div class= "container mt-4" id="tableOfOrders">
      <b-table 
        bordered
        small
        fixed
        :fields="fields"
        :items="orders"
        :filter-included-fields="selected"
        :filter="filter"
        :per-page="perPage"  
        :current-page="currentPage"     
        >
  <!-- Table Actions-->
            <!-- Info modal -->
            <template #cell(actions)="row">
              <b-button class="btn button-xs btn-info mr-1" @click="info(row.item ,$event.target)">
                Información
              </b-button>
                    
              <b-modal :id="infoModal.id" size="xl" title= "Información del pedido" ok-only>
                <pre>{{ infoModal.content }}</pre>
              </b-modal>

            <!-- Change state -->
            <b-form-select class= "form-xs" v-model="row.item.name" :options= "options" size="sm" @change="updateState(row.item)">
              Estado
            </b-form-select>
          </template>

      </b-table>
    </div>
  </b-container>
</template>

<script>
export default {
  name: 'OrdersTable',
  data() {
      return {
        fields: [
          {
           key: 'reference',
           label: 'Identificador',
          },
          {
           key: 'date_add',
           label: 'Fecha',
          },
          {
            key: 'firstname',
            label: 'Nombre',
          },
          {
           key: 'lastname',
           label: 'Apellidos',
          },
          {
           key: 'address1',
           label: 'Dirección de envío 1',
          },
          {
           key:  'address2',
           label: 'Dirección de envío 2',
          },
          {
           key:  'city',
           label: 'Ciudad',       
          },
          {
           key: 'country_name',
           label: 'País',
          },
          {
           key: 'product_name',
           label: 'Productos',
          },
          {
           key: 'product_quantity',
           label: 'Cantidad',
          },
          {
           key: 'name',
           label: 'Estado del pedido',  
          },
          {
           key: 'actions',
           label: 'Editar',
          },
        
        ],
        orders: {},
        selected: '',
        filter: '',
        polling: null,
        infoModal: {
          id: 'info-modal',
          content: ''
        },
        state_selected: "",
        options: [
        {value: '1', text: 'Pago pendiente'},
        {value: '2', text: 'Pago aceptado'},
        {value: '3', text: 'Preparación en proceso'},
        {value: '4', text: 'Enviado'},
        {value: '5', text: 'Entregado'},
        {value: '6', text: 'Cancelado'},
        {value: '7', text: 'Reembolso'},
        {value: '8', text: 'Error en el pago'}
        ],
        perPage: 5,
        pageOptions: [5, 10, 15, { value: this.totalRows, text: "Mostrar todos" }],
        totalRows: 1,
        currentPage: 1,

      }
  },
  mounted() {
    this.getOrders();
    this.renderData();

  },
  methods: {

      //Get all orders from DB
      getOrders() {
          this.$http.get('http://127.0.0.1:8000/api/orders/')
          .then(Response => {
              this.orders = Response.body.order;
              this.totalRows = Response.body.order.length;
            }, error => {
              console.log("ERROR = ", error);
            });
      },

      updateState(item) {
         const body = JSON.stringify({
          current_state: item.name,
         });
          
          this.$http.put(`http://127.0.0.1:8000/api/update/${item.id_order}`, body)
          .then(Response => {
             console.log("Current State= ", Response.body.order);  
              this.getOrders();
              
            }, error => {
              console.log("ERROR = ", error);
            });
      },


      //Actualize DB every 3 seconds
      renderData() {
        this.polling = setInterval(() => {
            this.getOrders()
        }, 3000)
      },

      //Information displayed in modal
      info(item, button) {
        this.infoModal.content = `
        Referencia: ${item.reference} 
        Fecha: ${item.date_add} 
        Nombre y Apellidos: ${item.firstname} ${item.lastname} 
        Dirección de envío: ${item.address1}, ${item.address2}, ${item.city}, ${item.country_name}, 
        Productos: ${item.product_name}
        Cantidad: ${item.product_quantity} 
        Estado del Pedido: ${item.name}`

        this.$root.$emit('bv::show::modal', this.infoModal.id, button)
      },

       resetInfoModal() {
        this.infoModal.content = ''
      },

      
  },

  //Prevent timer running after switching to another page
  beforeDestroy(){
    clearInterval(this.polling)
  },

  created() {
    this.renderData()
  }
 
}

</script>


<style scoped>

.button-xs {
  height: 22px;
  padding: 2px 5px;
  font-size: 12px;
  line-height: 1.5; 
  border-radius: 3px;
}
.form-xs {
  width: 75%;
  
}
</style>
