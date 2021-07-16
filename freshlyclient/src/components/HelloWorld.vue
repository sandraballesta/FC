<template>
  <b-container>

    <div class= "container mt-4" id="tableOfOrders">
      <b-table 
        bordered
        small
        fixed
        :fields="fields"
        :items="orders"
        >

          <template>
            <b-button variant="danger">Editar</b-button>
          </template>
      </b-table>
      <h1>{{test}}</h1>
    </div>
  </b-container>
</template>

<script>
export default {
  name: 'HelloWorld',
  data() {
      return {
        test: 0,
        fields: [
          {
           key: 'reference',
           label: 'Identificador',
           sortable: true
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
           key: 'reference',
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
           label: 'Editar',
          },
        
        ],
        orders: {}
      }
  },
  mounted() {
    this.getOrders();
  },
  methods: {
        getOrders() {
        this.$http.get('http://127.0.0.1:8000/api/orders/')
          .then(Response => {
            this.orders = Response.body.order;
            console.log(Response.body.order[0].reference);

          }, error => {
            console.log("ERROR = ", error);
          });
      },

  }
}
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h3 {
  margin: 40px 0 0;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>
