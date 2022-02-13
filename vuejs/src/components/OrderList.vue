<template>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8" style="padding-top: 6vh">
        <h3>Orders</h3>
        <table class="table table-striped" style="margin-top: 3vh">
            <thead>
                <tr>
                <th scope="col">Delivery Date </th>
                <th scope="col">Total price</th>
                <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(order, index) in orders" :key="index">
                <td>Mark</td>
                <td>Otto</td>
                <td >@mdo</td>
                <td> <button class="btn btn-success" @click="setToReady">Ready</button></td>
                </tr>
            </tbody>
        </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</template>


<script>
import { notify } from "@kyvg/vue3-notification";
import OrderService from '../services/order.service';
export default {
  name: "OrderList",
  props: {
    data: {
        type: Object,
        required: true,
        default: () => {}
    }
  },
  data () {
   return {
    orders: []
  }
  },
  methods: {
      setToReady() {
        OrderService.changeOrderStatus()
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
      this.orders = this.data
  }
}  
</script>


<style>

</style>