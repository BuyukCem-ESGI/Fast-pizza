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
          <td><button v-if="order.status =='PROGRESS'" class="btn btn-success" @click="setToReady(order.id)">Ready</button></td>
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
  name: "PizzeriaOrders",
  data () {
   return {
    orders: []
  }
  },
  methods: {
    refreshData() {
      OrderService.getPizzeriaOrders()
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
    },
    setToReady(id) {
      OrderService.changeOrderStatus(id)
          .then(response => {
            if(response.status === 201) {
              notify({
                title: "SUCCESS",
                text: "change taken into account",
                type: 'success'
              });
            }
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