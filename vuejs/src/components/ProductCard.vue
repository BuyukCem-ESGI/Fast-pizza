<template>
 <ProductDetailsModal v-if="modalVisible" @close="onClose" :data="modalData"/>
  <div class="card product-card" v-bind:class="getHeight">
    <img :src="data.image" class="card-img-top" @click="detail">
    <div class="row detail-container">
      <div class="col-md-9"><span>{{data.name}}</span></div>
      <div class="col-md-3 right-side">{{data.price}}€</div>
    </div>
    <div class="row" v-if="productExist">
      <div class="col-md-12" style="padding-left: 20px">
        <h5>Supplement</h5><br>
          <select v-model="selectedTypeValue" @change="onChange()" style="height: 40px;width: 95%;font-size: 16px" v-bind:class="{ error: error }">
            <option style="font-size: 17px" v-for="(item, key) in productData.products" :value="item" :key="key">{{item.name}}</option>
          </select>
      </div>
    </div>
    <div class="row" style="padding: 0px 10px 15px 10px">
      <div class="col-md-8"></div>
      <div class="col-md-4 right-side" style="margin-top: 15px">
        <button class="btn bucket-btn" v-on:click="addToCart()">+</button>
      </div>
    </div>
  </div>
</template>

<script>
import ProductDetailsModal from './ProductDetailsModal.vue'
export default {
  name: "ProductCard",
  components: {
    ProductDetailsModal
  },
  props: {
        productData: {
            type: Object,
            required: true,
            default: () => {}
        }
  },
 data() {
      return {
        selectedTypeValue: null,
        error: false,
        data: {},
        modalVisible: false,
       modalData: null,
      }
  },
  methods: {
    detail() {
      this.modalData = this.data
      this.modalVisible = true
    },
    addToCart() {
      if( this.selectedTypeValue ) {
        this.data.supllement = this.selectedTypeValue
        this.$store.dispatch("cart/addToCart", {
          data: this.data,
          quantity: 1
        }).then(() => {
          this.$emit("inputData",this.$store.state.cart.products);
        })
      }else {
        this.error = true
      }
    },
    onChange() {
      if(this.selectedTypeValue === null) {
        this.error = true
      }else {
        this.error = false
      }
    },
    onClose() {
        this.modalVisible = false;
      }
  },
  computed: {
    getHeight() {
      return this.productData.products && this.productData.products.length > 0 ? 'menu-height' : 'other-height'
    },
    productExist() {
      return this.productData.products && this.productData.products.length
    }
  },
  created() {
    this.data = this.productData
  }
};
</script>

<style scoped>
    .product-card {
      margin-top: 30px
    }
    .card-img-top {
      height: 250px;
      width: 100%;
      cursor: pointer;
    }
    .right-side {
      text-align: end;
    }
    .detail-container {
      padding: 15px 10px 0px 10px;
    }
    .bucket-btn {
    width: 22px;
    height: 20px;
    background-color: #F3BA00 !important;
    border-radius: 5px;
    text-align: center;
    font-weight: 900 !important;
    padding: unset !important;
    line-height: 1 !important;
    color: #fff !important;
    font-size: 1.2rem !important;
    }
    .bucket-btn:hover {
        color: #fff
    }
    .menu-height {
      height: 480px
    }
    .other-height {
      height: 350px;
    }
    .error {
      border-color: red
    }
</style>
