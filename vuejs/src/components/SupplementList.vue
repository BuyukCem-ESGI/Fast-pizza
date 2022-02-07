<template>
<div class="row">
  <div class="col-md-8">
    <h4>Supplements </h4><br>
  </div>
</div>
  <SupplementProductForm @clickBtn="addNew" :data="formData" />
  <SupplementProductModal v-if="modalVisible" @close="onClose" :data="modalData"/>
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
          <span>Select* :  {{ supplement.requiredChoice ? " Required" : " Optional" }}</span><br>
          <span>Max choice: {{ supplement.maxChoice }}</span>
        </div>
        </div>
      </div>
  </div>
</template>

<script>
import SupplementProductModal from './SupplementProductModal.vue';
import SupplementProductForm from './SupplementProductForm.vue';
const defaultTodos = [
  {
    name: "Name 1",
    requiredChoice: true,
    maxChoice: 25,
    supplements: [
        {
            name: "Supplement 1",
            price: 12,
            freeWithProduct: true,
        },
        {
            name: "Supplement 3",
            price: 12,
            freeWithProduct: true,
        },
    ],
  },
  {
    name: "Name 2",
    requiredChoice: false,
    maxChoice: 25,
    supplements: [
       
        {
            name: "Supplement 3",
            price: 12,
            freeWithProduct: true,
        },
        {
            name: "Supplement 4",
            price: 12,
            freeWithProduct: true,
        },
    ],
  },
  {
    name: "Name 3",
    requiredChoice: true,
    maxChoice: 25,
    supplements: [
        {
            name: "Supplement 1",
            price: 12,
            freeWithProduct: true,
        },
        {
            name: "Supplement 4",
            price: 12,
            freeWithProduct: true,
        },
    ],
  },
  
];

export default {
  name: "SupplementList",
  components: {
      SupplementProductModal,
      SupplementProductForm
  },
  inject: ['choices'],
  data () {

    return {
      formData: {
        title: "",
        maxChoice: null,
        value: [],
        picked: 'required',
        btnTitle: 'Add supplement',
        requiredChoice: true,
      },
      supplements: [],
      todos: [],
      modalVisible: false,
      modalData: null,
      modalIndex: null,
    }

  },

  created() {
    this.todos = this.choices
    this.supplements = defaultTodos
  },
  methods: {
      addNew(data) {
        this.supplements.push(data);
        this.$emit('supplementToParent', this.supplements);
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
        this.$emit('supplementToParent', this.supplements);
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