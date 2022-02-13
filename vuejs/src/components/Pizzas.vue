<template>
  <div class="col-md-8">
    <h2> Pizzas </h2>
    <div class="row">
      <div class="col-xs-8 col-md-3 container-card" v-for="(product, i) in products" :key="i">
        <product-card :product-data="product">
        </product-card>
      </div>
    </div>
  </div>
</template>

<script>
import ProductService from '../services/product.service'
import ProductCard from "./ProductCard.vue";
export default {
  name: "Pizzas",
  components: {
    ProductCard
  },
  data() {
    return {
      content: "",
      products: [

      ]
    };
  },
  provide() {
    return {
      products: this.products
    }
  },
  mounted() {
    try{
          ProductService.getPizzas().then((response) => {
              this.products = response.data
          })
      }catch(e) {
          console.log(e)
      }
  }
};
</script>

