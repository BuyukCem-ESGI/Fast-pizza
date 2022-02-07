<template>
    <div class="row">
        <div style="width: 250px;float: left;margin-left: 20px">
          <label for="name">Choices name</label><br>
          <input
            id="title"
            v-model="title"
            type="text"
            name="title"
          >
        </div>
        <div style="width: 250px;float: left;">
          <label for="maxChoice">Limit</label><br>
          <input
            id="maxChoice"
            v-model="maxChoice"
            type="number"
            name="maxChoice"
            min="0"
          >
        </div>
    </div>
  <div class="row" style="padding: 15px 20px 20px 20px">
  <div id="v-model-radiobutton">
    <p>Choice</p>
    <input type="radio" id="one" value="required" v-model="picked"/>
    <label for="one" class="radio-text">Required</label>
    <input type="radio" id="two" value="optional" v-model="picked" style="margin-left: 30px"/>
    <label for="two" class="radio-text">Optional</label>
</div>
  </div>

    <div class="row">
    <div class="col-md-8">
    <p>Select supplements</p>
        <Multiselect
            v-model="value"
            mode="tags"
            :close-on-select="false"
            :searchable="true"
            :create-option="true"
            :options="selectedSupplements"
        />
    </div>
  </div>
    <div
        v-if="showValidError.length > 0 "
        class="row"
        style="margin-top: 20px;"
   >
   <div class="col-md-9 alert-danger" style="padding-top: 10px">
    <ul v-for="(error,i) in showValidError" :key="i">
      <li>{{error}}</li>
    </ul>
   </div>
  </div>
  <div class="row">
    <button class="btn btn-success" style="margin: 20px" @click="btnClick">{{btnTitle}}</button>
  </div>
</template>


<script>

import Multiselect from '@vueform/multiselect';

const defaultSupplements = [
    {
        name: "Supplement 1",
        price: 12,
        freeWithProduct: true,
        imagesUrl: 'https://placeimg.com/640/480/arch'
    },
    {
        name: "Supplement 2",
        price: 12,
        freeWithProduct: true,
        imagesUrl: 'https://placeimg.com/640/480/arch'
    },
        {
        name: "Supplement 3",
        price: 12,
        freeWithProduct: true,
        imagesUrl: 'https://placeimg.com/640/480/arch'
    },
    {
        name: "Supplement 4",
        price: 12,
        freeWithProduct: true,
        imagesUrl: 'https://placeimg.com/640/480/arch'
    },
];

export default {
  name: "SupplementProductForm",
  components: {
      Multiselect,
  },
  props: {
        data: {
            type: Object,
            required: true,
            default: () => {}
        }
  },
  data () {

    return {
      title: "",
      maxChoice: null,
      value: [],
      selectedSupplements: [],
      showValidError: [],
      picked: '',
      btnTitle: ''
    }

  },
  mounted() {
    this.selectedSupplements = defaultSupplements.map((item) => {
        item.value = item.name.toLowerCase()
        item.label = item.name 
        return item
     });
    this.title = this.data.title
    this.maxChoice = this.data.maxChoice
    this.value = this.data.value
    this.btnTitle = this.data.btnTitle
    this.picked = this.data.requiredChoice ? "required" : "optional"
  },
  methods: {
      btnClick() {
        this.showValidError = []
        if (this.value.length === 0)
            this.showValidError.push("Select supplements")
        if (this.title.length === 0)
            this.showValidError.push("Enter choices title")
        if (isNaN(parseInt(this.maxChoice)))
              this.showValidError.push("Enter max choice")
        if (this.showValidError.length <= 0) {
          const selectedSups = this.selectedSupplements.filter((item) =>  this.value.includes(item.name.toLowerCase()))
          this.$emit('clickBtn', {
            name: this.title,
            requiredChoice: true,
            maxChoice: this.maxChoice,
            supplements: selectedSups
          })
          this.title = "";
          this.maxChoice = "";
          this.value = [];
        }
      }
  },
  computed: {
    getBtnTitle() {
      return this.btnTitle
    }
  }
}

</script>


<style src="@vueform/multiselect/themes/default.css">
  .radio-text {
    margin-left: 10px
  }
</style>