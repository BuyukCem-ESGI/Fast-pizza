<template>
  <div class="col-md-8">
    <h2> Extras  </h2>
    <div class="row">
      <div class="col-xs-8 col-md-3 container-card" v-for="(product, i) in products" :key="i">
        <product-card :product-data="product">
        </product-card>
      </div>
    </div>
  </div>
</template>

<script>

import ProductCard from "./ProductCard.vue";
import ProductService from "@/services/product.service";
export default {
  name: "Extra",
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
      ProductService.getExtras().then((response) => {
        this.products = response.data
      })
    }catch(e) {
      console.log(e)
    }
  },
};
</script>

