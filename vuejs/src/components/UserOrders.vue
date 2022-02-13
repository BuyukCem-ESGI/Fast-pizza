<template>
  <div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8" style="padding-top: 6vh">
      <h3>Orders</h3>
      <table class="table table-striped" style="margin-top: 3vh">
        <thead>
        <tr>
          <th scope="col">Delivery Date </th>
          <th scope="col">Status</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="(order, index) in orders" :key="index">
          <td>{{order.created_at}}</td>
          <td>{{order.status}}</td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="col-md-2"></div>
  </div>
</template>

<script>

import OrderService from '../services/order.service'
import { notify } from "@kyvg/vue3-notification";
export default {
  name: "UserOrders",
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
        console.log(response.data)
      })
      .catch(function (error) {
            notify({
             title: "ERROR",
             text: error.response,
             type: 'error'
            });
      }); 
    }
  },
  created() {
      this.refreshData()
  }
}  
</script>


<style>

</style>