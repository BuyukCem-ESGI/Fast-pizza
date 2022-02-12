<template>
    <div class="cart">
        <h3 style="font-weight: bold">Votre panier</h3>
        <div style="height: 90%;overflow-y: scroll;">
          <div class="" style="width: 100%;height: 92px;margin-top: 10px;padding: 10px" v-for="(product, index) in getProducts" :key="index">
            <div class="" style="width: 30%;height: 100%;float: left">
              <img  style="height: 85px;width: 100%" :src="product.data.image" />
            </div>
            <div class="" style="width: 70%;height: 100%;float: right;text-align: start;padding: 6px;">
              <span>{{product.data.name}}</span><br>
              <p style="margin-bottom: unset">
              <span v-if="product.quantity > 1" @click="changeQuantity(product.data._id,'minus')" style="margin-left: 7px;font-weight: bold;font-size: 25px;cursor: pointer"> - </span> 
              <span>{{product.quantity}}</span> 
              <span @click="changeQuantity(product.data._id,'plus')" style="margin-left: 7px;font-weight: bold;font-size: 20px;cursor: pointer">+</span> 
              <span style="margin-left: 7px">{{product.data.price * product.quantity}} €</span>
              </p>
              <a @click="remove(product.data._id)" style="float: right;cursor: pointer;text-decoration: underline">supprimer</a>
            </div>
          </div>
        </div>
        <div style="height: 5%;">
          <button class="btn btn-dark" @click="toCart" style="width: 90%;">Payer</button>
        </div>
    </div>
</template>

<script>
export default {
  name: "DynamicCart",
  components: {
  },
    data () {

    return {
       products: []
    }

  },
  methods: {
    remove(id) {
      this.$store.dispatch("cart/removeFromCart",id).then(() => {
        this.products =  this.$store.state.cart.products;
      })
    },
    changeQuantity(id,action) {
      this.$store.dispatch("cart/changeQuantity",{
        action: action,
        id: id
        }).then(() => {
        this.products =  this.$store.state.cart.products;
      })
    },
    toCart() {
      this.$router.push('/cart');
    }
  },
  computed: {
    getProducts() {
      return this.products
    }
  },
  created() {
    this.products =  this.$store.state.cart.products;
  },
  watch: {
    inputData: function(data) {
      this.products = data;
    }
  }
}  
</script>

<style>
    .cart {
        position: fixed;
        width: 350px;
        border: 2px #eee solid;
        margin-left: 5%;
        float:right;
        top: 6%;
        height: 93vh;
        text-align: center;
        background: #eee
    }
</style>