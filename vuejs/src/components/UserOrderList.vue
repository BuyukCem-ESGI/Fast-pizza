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
                </tr>
            </tbody>
        </table>
        </div>
        <div class="col-md-2"></div>
    </div>
</template>


<script>
export default {
  name: "UserOrderList",
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
  created() {
      this.orders = this.data
  }
}  
</script>


<style>

</style>