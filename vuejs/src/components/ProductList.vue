<template>
    <div class="row">
        <div class="col-xs-8 col-md-3 container-card" v-for="(product, i) in products" :key="i">
            <product-card :product-data="product">
            </product-card>
        </div>
    </div>
</template>

<script>
import ProductCard from "./ProductCard.vue";

export default {
  name: "ProductList",
  components: {
      ProductCard
  },
  inject: ['products']
};
</script>



