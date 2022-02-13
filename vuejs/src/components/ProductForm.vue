<template>
<div class="row" style="margin-top: 5vh">
  <div class="col-md-2"></div>
  <div class="col-md-10">
      <div class="row">
         <button class="btn btn-success" @click="back"><font-awesome-icon icon="arrow-left" /></button>
      </div>
      <div class="row">
        <div class="col-md-8 alert-danger" v-if="showValidError.length > 0" style="padding-top: 10px">
          <ul v-for="(error,i) in showValidError" :key="i">
            <li>{{error}}</li>
          </ul>
        </div>
      </div>
      <div class="row">
        <div class="col-md-5">
          <div class="form-group">
            <label for="name">Name</label>
            <input v-model="name" type="text" class="form-control name" />
          </div>
          <div class="form-group">
            <label for="description">Description</label>
            <textarea v-model="description" placeholder="add product description" class="form-control description" rows="6"></textarea>
          </div>
        </div>
      </div>
      <div class="row">
         <div class="col-md-5">
          <label for="price">Price</label><br>
          <input
          class="input-width"
          id="price"
          v-model="price"
          type="number"
          name="price"
          min="0"
          >
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <label v-show="!showImage" class="text-reader">
            Select cover picture
            <input type="file" @change="onFileChange" accept="image/png, image/gif, image/jpeg">
          </label>
          <div v-show="showImage">
            <img :src="image" />
            <button class="btn btn-danger" @click="removeImage"><font-awesome-icon icon="trash" /></button>
          </div>
        </div>
      </div>
      <!--
      <div class="row">
        <div class="col-md-8">
          <TypeList v-on:childToParent="getTaillesData" />
        </div>
      </div> -->
 
      <div class="row">
        <div class="col-md-4">
          <h4>Type of product</h4><br>
          <select v-model="selectedTypeValue" style="height: 40px;width: 200px;font-size: 16px">
            <option style="font-size: 17px" v-for="(item, key) in types" :value="item" :key="key">{{item}}</option>
          </select>
        </div>
      </div>

      <div class="row"  v-show="showProduct">
        <div class="col-md-8">
        <p>Select products</p>
            <Multiselect
                v-model="value"
                mode="tags"
                :close-on-select="false"
                :searchable="true"
                :create-option="true"
                :options="selectedProducts"
            />
        </div>
      </div>
     <!-- 
      <div v-show="showSupplement" class="row">
        <div class="col-md-12">
          <SupplementList  v-on:supplementToParent="getSupplementData"/>
        </div>
      </div>
      -->
      <div class="row" style="margin-bottom: 50px">
      <div class="col-md-2">
          <button class="btn btn-primary btn-block" type="submit" @click="save">
            SAVE
        </button>
      </div>
      </div>
  </div>
</div>
</template>

<script>

import MenuService from "../services/menu.service"
import ProductService from '../services/product.service'
import Multiselect from '@vueform/multiselect'
export default {
  name: "ProductForm",
  components: {
    Multiselect
  },
  data() {
    return {
      showValidError: [],
      message: "",
      selectedTypeValue: "Pizza",
      price: null,
      value: [],
      selectedProducts: [],
      //tailles: [],
      //taillesData: [],
      types: ["Pizza","Menu","Supplement"],
      image: '',
      validImage: true,
      name: "",
      description: "",
      productsData: [],
      imagesArray: null
    };
  },
  computed: {
    showImage() {
      return this.image !== '';
    },
    showProduct() {
      return this.selectedTypeValue === "Menu"
    }
  },
  mounted() {
    ProductService.getAllProducts().then((response)=>{
       this.selectedProducts = response.data.map((item) => {
         item.value = "products/"+item.id
         item.label = item.name
         return item
       });
    })
    if (!this.$store.state.auth.status.loggedIn) {
      this.$router.push("/login");
    } else {
      if (this.$route.params.id) {
        ProductService.getProductById(this.$route.params.id).then((response)=> {
          console.log(response.data)
          this.name = response.data.name
          this.selectedTypeValue = response.data.typeProduct
          this.description = response.data.description
          this.price = response.data.price
          this.image = response.data.image
        })
      }
    }
  },
  methods: {
    save() {
      this.showValidError = []
      if (this.name.trim().length <= 0) this.showValidError.push("Add the name")
      if (this.description.trim().length <= 0) this.showValidError.push("Add the description")
      if(this.selectedTypeValue === "Menu" && this.value.length <= 0) this.showValidError.push("Add product")
      if (isNaN(parseInt(this.price)))
              this.showValidError.push("Enter price")
      if(this.showValidError.length <= 0) {
        const jsonData = {
          name: this.name,
          description: this.description,
          typeProduct: this.selectedTypeValue,
          price: String(this.price),
          products: this.value,
          image: this.image.toString()
        }
        if(this.$route.params.id) {
          ProductService.updateProduct(jsonData,this.$route.params.id).then((response) => {
            console.log(response)
          })
        }else {
          console.log(jsonData)
          if(this.selectedTypeValue === "Menu") {
            MenuService.addMenus(jsonData).then((response)=> {
              console.log(response)
            })
          }else{
            ProductService.addProduct(jsonData)
          }
        }
        this.imagesArray = null
        this.name = ""
        this.description = ""
        //this.taillesData = []
        this.selectedTypeValue = "Pizza"
        this.productsData = []
        this.image = ''
        //this.tailles = []
      }
    },
    // getTaillesData(data) {
    //   this.taillesData = data
    // },
    getSupplementData(data) {
      this.productsData = data
    },
    onFileChange(e) {
      var files = e.target.files || e.dataTransfer.files;
      const allowedExtension = ['jpeg', 'jpg', 'png', 'gif'];
      if (!files.length )
        return;
      this.validImage = allowedExtension.includes(files[0].type.split('/')[1])
      if (!this.validImage)
        return;
      this.createImage(files[0]);
    },
    createImage(file) {
      let reader = new FileReader();
      reader.onload = (e) => {
        this.image = e.target.result;
        this.imagesArray = file
      };
      reader.readAsDataURL(file);
    },
    removeImage: function () {
      this.image = '';
    },
    back() {
      this.$router.push("/product-handler");
    }
  },
  provide() {
    return {
      //tailles: this.tailles,
      choices: []
    }
  },
};
</script>

<style lang="scss">
@import "@vueform/multiselect/themes/default.scss";
  .btn-primary {
    background-color: #1AC073 !important;
    border-color: #1AC073 !important;
    font-weight: bold;
    font-size: 20px
  }
  .row {
    padding-top: 2vh
  }
  .card-header {
    background-color: #1AC073
  }
  .titlte {
    font-weight: bold;
    text-align: center;
    color: #fff;
  }
  .text-reader {
  position: relative;
  overflow: hidden;
  display: inline-block;
  background-color: #1AC073;
  color: #fff;
  border-radius: 5px;
  padding: 8px 12px;
  cursor: pointer;
}
.text-reader input {
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  opacity: 0;
}

img {
  width: 400px;
  display: block;
  height: 400px;
  margin-bottom: 5px;
}

</style>