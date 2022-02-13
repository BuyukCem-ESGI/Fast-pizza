<template>
    <OrderList  :data="orders"/>
</template>

<script>
import OrderList from './OrderList.vue'
import OrderService from '../services/order.service'
import { notify } from "@kyvg/vue3-notification";
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
    refreshData() {
        OrderService.getUserOrders()
      .then(response => {
          this.orders = response.data
      })
      .catch(function (error) {
        if (error.response) {
            notify({
             title: "ERROR",
             text: error.response,
             type: 'error'
            });
        }
      }); 
    }
  },
  created() {
      this.refreshData()
  },
  mounted() {
      //setInterval(this.refreshData, 5000);
  }
}  
</script>


<style>

</style>