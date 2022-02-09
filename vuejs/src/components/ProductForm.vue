<template>
<div class="row">
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
      <div class="row">
        <div class="col-md-8">
          <TypeList v-on:childToParent="getTaillesData" />
        </div>
      </div>
      <!-- supplement list
      <div class="row">
        <div class="col-md-4">
          <h4>Type of product</h4><br>
          <select v-model="selectedTypeValue" style="height: 40px;width: 200px;font-size: 16px">
            <option style="font-size: 17px" v-for="(item, key) in types" :value="item" :key="key">{{item}}</option>
          </select>
        </div>
      </div>
      
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
import TypeList from "./TypeList.vue"
import ProductService from '../services/product.service'

export default {
  name: "ProductForm",
  components: {
    TypeList,
  },
  data() {
    return {
      showValidError: [],
      message: "",
      selectedTypeValue: "Product",
      tailles: [],
      taillesData: [],
      image: '',
      validImage: true,
      name: "",
      description: "",
      supplementsData: [],
      imagesArray: null
    };
  },
  computed: {
    showImage() {
      return this.image !== '';
    },
    showSupplement() {
      return this.selectedTypeValue === "Menu"
    }
  },
  mounted() {
    if (!this.$store.state.auth.status.loggedIn) {
      this.$router.push("/login");
    } else {
      if (this.$route.params.id) {
        ProductService.getProductById().then((response)=> {
          console.log(response)
        })
      }
    }
  },
  methods: {
    save() {
      this.showValidError = []
      if (this.name.trim().length <= 0) this.showValidError.push("Add the name")
      if (this.description.trim().length <= 0) this.showValidError.push("Add the description")
      if (this.taillesData.length <= 0) this.showValidError.push("Add size and price")
      if(this.selectedTypeValue === "Menu" && this.supplementsData.length <= 0) this.showValidError.push("Add supplement")

      if(this.showValidError.length <= 0) {
        const jsonData = {
          name: this.name,
          description: this.description,
          type: this.selectedTypeValue,
          price: this.taillesData,
          supplements: this.supplementsData,
          image: this.image.toString()
        }
        if(this.$route.params.id) {
          ProductService.updateProduct(jsonData,this.$route.params.id).then((response) => {
            console.log(response)
          })
        }else {
           ProductService.addProduct(jsonData)
        }
        this.imagesArray = null
        this.name = ""
        this.description = ""
        this.taillesData = []
        this.selectedTypeValue = "Product"
        this.supplementsData = []
        this.image = ''
        this.tailles = []
      }
    },
    getTaillesData(data) {
      this.taillesData = data
    },
    getSupplementData(data) {
      this.supplementsData = data
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
      tailles: this.tailles,
      choices: []
    }
  },
};
</script>

<style scoped>
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