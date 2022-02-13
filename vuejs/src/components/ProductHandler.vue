<template>
    <div class="row" style="margin-top: 5vh">
        <div class="col-md-2"></div>
        <div class="col-md-8">
            <div class="row">
                <button class="btn btn-success" style="margin: 20px" @click="create">Create new product</button>
            </div>
            <span v-if="empty" class="text-danger">There is no product</span>
            <div class="row" v-for="(product, index) in getProductList" :key="index">
                <div class="col-md-8 card" style="margin: 10px">
                <div class="row" style="padding: unset">
                    <div class="col-md-12" style="text-align: end">
                        <button class="btn btn-ligth" @click="removeProduct(product,index)"><font-awesome-icon icon="trash" /> </button>
                    </div>
                </div>
                <div class="row sup-container" @click="redirect(product)" style="cursor:pointer; ">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-2" style="heigth: 100px;padding: unset">
                                <img  style="height: 100px; width: 100%" :src="product.image" />
                            </div>
                            <div class="col-md-9" style="margin-left: 20px;padding: unset">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h4>{{ product.name }}</h4>
                                        <h5>{{ product.description }}</h5>
                                        <h6>{{ product.price }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
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
        productList: [],
        empty: false
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
            console.log(response.data)
              this.productList = response.data
              this.empty = false
          })

      }catch(e) {
        this.empty = true
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
          this.$router.push("/product-form/"+product.id);
      },
      create() {
          this.$router.push("/product-form");
      }
      
  },
};
</script>

<style>
  .sup-container {
    padding: 0px 15px 15px 15px
  }
</style>