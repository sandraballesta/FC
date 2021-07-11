<template>
  <div>
    <b-table 
      :items="items">
    </b-table>
    <h1>{{ order }}</h1>
  </div>
</template>

<script>
export default {
  name: 'HelloWorld',
  props: {
  },
  data() {
      return {
        items: [
          { age: 40, first_name: 'Dickerson', last_name: 'Macdonald' },
          { age: 21, first_name: 'Larsen', last_name: 'Shaw' },
          { age: 89, first_name: 'Geneva', last_name: 'Wilson' },
          { age: 38, first_name: 'Jami', last_name: 'Carney' }
        ],
        order: 0
      }
  },
  methods: {
        getOrders() {
        this.$http.get('http://127.0.0.1:8000/api/orders/')
          .then(Response => {
            this.order = Response.body.order[0].reference;

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
