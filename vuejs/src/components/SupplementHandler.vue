<template>
<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-7">
      <div class="row">
        <div class="col-md-8">
          <h4>Supplements </h4><br>
        </div>
      </div>
      <div class="row">
        <div class="col-md-8">
          <SupplementForm @supplementHandler="addNew" :data="formData" />
        </div>
      </div>
        <SupplementModal v-if="modalVisible" @close="onClose" :data="modalData"/>
      <div class="row" v-for="(supplement, index) in getSupplementsList" :key="index">
            <div class="col-md-8 card" style="margin: 10px">
              <div class="row">
                  <div class="col-md-12" style="text-align: end">
                    <button class="btn btn-ligth" @click="supplements.splice(index, 1)"><font-awesome-icon icon="trash" /> </button>
                  </div>
              </div>
              <div class="row sup-container" @click="openModal(supplement,index)" style="cursor:pointer">
              <div class="col-md-9">
                <h4>{{ supplement.name }}</h4>
              </div>
              </div>
            </div>
        </div>
      </div>
    <div class="col-md-3"></div>
 </div>
</template>

<script>
import SupplementModal from './SupplementModal.vue';
import SupplementForm from './SupplementForm.vue';
import SupplementService from '../services/supplement.service'
export default {
  name: "SupplementHandler",
  components: {
      SupplementModal,
      SupplementForm
  },
  data () {

    return {
      formData: {
        name: "",
        price: null,
        image: '',
        imagesArray: null,
        validImage: true,
        btnTitle: 'Add supplement',
      },
      supplements: [],
      modalVisible: false,
      modalData: null,
      modalIndex: null,
    }

  },

  created() {

  },
  methods: {
      addNew(data) {
        const jsonData = {
          name: data.name,
          price: data.price,
          imageUrl: data.image.toString()
        }
        SupplementService.addSupplement(jsonData)
        this.supplements.push(data);
      },
      openModal(data,index) {
        data['btnTitle'] = "Update supplement"
        this.modalData = data
        this.modalVisible = true
        this.modalIndex = index
      },
      onClose(data) {
        this.modalVisible = false;
        this.supplements[this.modalIndex] = data
      }
  },
  computed: {
    getClose() {
      return this.modalVisible
    },
    getSupplementsList() {
      return this.supplements
    }
  }
};
</script>

<style>
  .sup-container {
    padding: 15px
  }

  .btn-ligth {
    color: red !important
  }
</style>