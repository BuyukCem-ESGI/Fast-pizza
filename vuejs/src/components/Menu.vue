<template>
  <div class="col-md-8">
    <h2> Menu </h2>
    <div class="row">
      <div class="col-xs-8 col-md-3 container-card" v-for="(product, i) in products" :key="i">
        <product-card :product-data="product">
        </product-card>
      </div>
    </div>
  </div>
</template>

<script>
import MenuService from "@/services/menu.service";
import ProductCard from "./ProductCard.vue";

export default {
  name: "Menu",
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
  created() {
    MenuService.getAllMenus().then(response => {
      this.products = response.data;
      console.log(this.products);
    });
  }
};
</script>

