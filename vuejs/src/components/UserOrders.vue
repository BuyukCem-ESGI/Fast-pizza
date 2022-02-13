<template>
    <OrderList  :data="orders"/>
</template>

<script>
import OrderList from './OrderList.vue'
export default {
  name: "UserOrders",
  components: {
      OrderList
  },
  data () {
   return {
    orders: []
  }
  },
  methods: {
      greetings() {
  console.log('Good Morning!');
}
  },
  created() {
      this.orders = ["1","2","3","5"]
  },
  mounted() {
      //setInterval(this.greetings, 5000);
  }
}  
</script>


<style>

</style>