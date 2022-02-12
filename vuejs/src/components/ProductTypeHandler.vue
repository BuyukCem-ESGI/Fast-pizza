<template>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-md-8">
          <h4>ProductTypes </h4><br>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
        <div class="row">
        <div class="col-md-4">
            <label for="name">Name</label><br>
        </div>
        </div>
            <div class="row">
            <div class="col-md-9">
                <input
                class="input-width"
                id="name"
                v-model="name"
                type="text"
                name="name"
                >
            </div>
            <div class="col-md-3">
                <button class="btn btn-success" @click="addNew">Add</button>
            </div>
        </div>
        </div>
      </div>
        <ProductTypeModal v-if="modalVisible" @close="onClose" :data="modalData"/>
      <div class="row" style="margin-top: 30px" v-for="(productType, index) in getProductTypesList" :key="index">
            <div class="col-md-8 card" style="margin: 10px">
              <div class="row">
                  <div class="col-md-12" style="text-align: end">
                    <button class="btn btn-ligth" @click="productTypes.splice(index, 1)"><font-awesome-icon icon="trash" /> </button>
                  </div>
              </div>
              <div class="row sup-container" @click="openModal(productType,index)" style="cursor:pointer">
              <div class="col-md-9">
                <h5>{{ productType.name }}</h5>
              </div>
              </div>
            </div>
        </div>
      </div>
    <div class="col-md-3"></div>
 </div>
</template>

<script>

const data = [
    {
        _id: "1",
        name: "type 1"
    },

    {
        _id: "2",
        name: "type 2"
    },
    {
        _id: "3",
        name: "type 3"
    },
    {
        _id: "4",
        name: "type 4"
    },
    {
        _id: "5",
        name: "type 5"
    }
]

//import ProductTypeService from '../services/product-type.service'
import ProductTypeModal from './ProductTypeModal.vue'
export default {
  name: "ProductTypeHandler",
  components: {
      ProductTypeModal,
  },
  data () {

    return {
      name: "",
      productTypes: [],
      modalVisible: false,
      modalData: null,
      modalIndex: null,
    }

  },

  created() {
      this.productTypes = data
  },
  methods: {
      addNew() {
        this.productTypes.push({_id: "6",name: this.name});
      },
      openModal(productType,index) {
          console.log("types ",productType)
        this.modalData = productType.name
        this.modalVisible = true
        this.modalIndex = index
      },
      onClose(data) {
          if(data === "") {
              console.log("same")
          }else {
              console.log("op")
              this.productTypes[this.modalIndex].name = data
          }
          this.modalVisible = false;
      }
  },
  computed: {
    getClose() {
      return this.modalVisible
    },
    getProductTypesList() {
      return this.productTypes
    }
  }
};
</script>
<style scoped>
    .input-width {
        height: 40px
    }
</style>
