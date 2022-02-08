<template>
    <div class="row">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <button class="btn btn-success" style="margin: 20px" @click="create">Create new product</button>
            </div>
            <div class="row" v-for="(product, index) in getProductList" :key="index">
                <div class="col-md-8 card" style="margin: 10px">
                <div class="row">
                    <div class="col-md-12" style="text-align: end">
                        <button class="btn btn-ligth" @click="removeProduct(product,index)"><font-awesome-icon icon="trash" /> </button>
                    </div>
                </div>
                <div class="row sup-container" @click="redirect(product)" style="cursor:pointer">
                <div class="col-md-9">
                    <h4>{{ product.name }}</h4>
                </div>
                </div>
                </div>
            </div>
        </div>
        <div class="col-md-2"></div>
    </div>
</template>

<script>

import ProductService from '../services/product.service'

export default {
  name: "ProductHandler",
  data() {
    return {
        productList: []
    };
  },
  computed: {
    getProductList() {
      return this.productList
    }
  },
  mounted() {
      try{
          ProductService.getAllProducts().then((response) => {
              this.productList = response.data
          })
      }catch(e) {
          console.log(e)
      }
  },
  methods: {
      removeProduct(product,index) {
          ProductService.deleteProduct(product.id).then( response => {
              console.log(response)
          })
          console.log(product,index)
      },
      redirect(product) {
          console.log(product)
          this.$router.push("/product-form/"+product._id);
      },
      create() {
          this.$router.push("/product-form");
      }
      
  },
};
</script>

<style>
  .sup-container {
    padding: 15px
  }
</style>