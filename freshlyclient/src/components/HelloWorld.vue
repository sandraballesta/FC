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
          <template #cell(actions)="row">
            <b-button v-b-modal.info-modal class="btn input-xs btn-info">
              Información
            </b-button>
            <b-button class="btn input-xs btn-secondary" @click="row.toggleDetails">
            Cambiar estado
            </b-button>
          
             <!-- Info modal -->
            <b-modal id="info-modal" centered title= "Información del pedido">
              Hello
            </b-modal>
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
      
  },
  beforeDestroy(){
    clearInterval(this.polling)
  },

  created() {
    this.renderData()
  }
 
}

</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>

/*Make Action Buttons extra small*/ 
.input-xs {
  height: 22px;
  padding: 2px 5px;
  font-size: 12px;
  line-height: 1.5; /* If Placeholder of the input is moved up, rem/modify this. */
  border-radius: 3px;
}
</style>
