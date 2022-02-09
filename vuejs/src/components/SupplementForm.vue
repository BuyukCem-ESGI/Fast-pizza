<template>
<div class="row">
    <div class="col-md-6">
        <label for="name">Name</label><br>
        <input
        class="input-width"
        id="name"
        v-model="name"
        type="text"
        name="name"
        >
    </div>
    <div class="col-md-6">
        <label for="price">Limit</label><br>
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
<div class="row" style="margin-top: 20px">
    <div class="col-md-6">
        <label v-show="!showImage" class="text-reader">
        Select picture
        <input type="file" @change="onFileChange" accept="image/png, image/gif, image/jpeg">
        </label>
        <div v-show="showImage">
        <img :src="image" />
        <button class="btn btn-danger" @click="removeImage"><font-awesome-icon icon="trash" /></button>
        </div>
    </div>
</div>
 <div class="row">
    <button class="btn btn-success" style="margin: 20px" @click="btnClick">{{btnTitle}}</button>
  </div>
</template>


<script>

export default {
  name: "SupplementForm",
  props: {
        data: {
            type: Object,
            required: true,
            default: () => {}
        }
  },
  data () {
    return {
      name: "",
      price: null,
      image: '',
      imagesArray: null,
      validImage: true,
      btnTitle: ''
    }
  },
  created() {
    this.btnTitle = this.data.btnTitle
    this.name = this.data.name
    this.price = this.data.price
    this.image = this.data.image
  },
  computed: {
    showImage() {
      return this.image !== '';
    },
    showSupplement() {
      return this.selectedTypeValue === "Menu"
    }
  },
  methods: {
    btnClick() {
        this.showValidError = []
        if (this.name.length === 0)
            this.showValidError.push("Enter supplement name")
        if (isNaN(parseInt(this.price)))
              this.showValidError.push("Enter price")
        if (this.showValidError.length <= 0) {
          this.$emit('supplementHandler', {
            name: this.name,
            price: this.price,
            image: this.image,
            imagesArray: this.imagesArray,
          })
          this.name = "";
          this.price = null;
          this.image = '';
          this.imagesArray = null;
        }
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
      var reader = new FileReader();
      reader.onload = (e) => {
        this.image = e.target.result;
        this.imagesArray = file
      };
      reader.readAsDataURL(file);
    },
    removeImage: function () {
      this.image = '';
    }
  }
}

</script>

<style>

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
  width: 200px;
  display: block;
  height: 200px;
  margin-bottom: 5px;
}

.input-width {
  width: 90%
}
</style>