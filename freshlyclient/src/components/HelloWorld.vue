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
            <b-dropdown id="dropdown-1" class="m-md-2 dropdown-xs" size="sm" text="Estado">
              <b-dropdown-item v-model="state_selected" >Entregado</b-dropdown-item>
              <b-dropdown-item>Enviado</b-dropdown-item>
              <b-dropdown-item>Preparación en proceso</b-dropdown-item>
            </b-dropdown>
          
     
          </template>

      </b-table>
    </div>
  </b-container>
</template>

<script>
export default {
  name: 'HelloWorld',
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
        length: 0,
        infoModal: {
          id: 'info-modal',
          content: ''
        }
      }
  },
  mounted() {
    this.getOrders();
    //this.renderData();

  },
  methods: {
      getOrders() {
          this.$http.get('http://127.0.0.1:8000/api/orders/')
          .then(Response => {
              this.orders = Response.body.order;
              console.log(Response.body.order[0].reference);
              this.length = Response.body.order.length;
              console.log(Response.body.order.length);


            }, error => {
              console.log("ERROR = ", error);
            });
      },

      renderData() {
        this.polling = setInterval(() => {
            this.getOrders()
        }, 10000)
      },
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
.dropdown-xs {
  position: relative;
  display: inline-block;
  
}
</style>
