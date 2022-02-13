<template>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
        <table class="table table-striped">
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
                <td > <button class="btn btn-success" @click="setToReady">Ready</button></td>
                </tr>
            </tbody>
        </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</template>


<script>
import { notify } from "@kyvg/vue3-notification";
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
        notify({
            title: "Authorization",
            text: "You have been logged in!",
            type: 'success'
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