<template>
  <div class="card product-card">
    <img :src="productData.img_url" class="card-img-top" @click="detail">
    <div class="row detail-container">
      <div class="col-md-9"><span>Title: {{productData.title}}</span></div>
      <div class="col-md-3 right-side">100€</div>
    </div>
    <div class="row detail-container">
      <div class="col-md-12">
        <span>Sélectionner la taille</span>
      </div>
    </div>
    <div class="row" style="padding: 0px 10px 15px 10px">
      <div class="col-md-9">
      <select v-model="selectedValue">
         <option v-for="(item, key) in filters" :value="item" :key="key">{{item}}</option>
     </select>
      </div>
      <div class="col-md-3 right-side">
        <button class="btn bucket-btn">+</button>
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: "ProductCard",
  props: {
        productData: {
            type: Object,
            required: true,
            default: () => {}
        }
  },
  data() {
      return {
        selectedValue: "Medium Classic",
        filters: ["Medium Classic", "XLarge classic"],
      }
  },
  methods: {
    detail() {
        alert(this.selectedValue)
    }
  },
};
</script>

<style>
    .product-card {
      height: 365px;
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
      padding: 15px 10px 0px 10px
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
</style>