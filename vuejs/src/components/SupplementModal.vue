<template>
<!-- Modal -->
<transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" @click="closeModal(data)">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body container">
                  <SupplementForm @supplementHandler="closeModal" :data="formData" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
</template>




<script>
import SupplementForm from './SupplementForm.vue';
export default {
  name: "SupplementModal",
  components: {
    SupplementForm
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
      formData: {
        name: "",
        price: null,
        image: '',
        imagesArray: null,
        btnTitle: ''
      },
    }

  },
  methods: {
    closeModal(data) {
      this.$emit('close',data)
    }
  },
  created() {
      this.formData.name = this.data.name
      this.formData.price = this.data.price
      this.formData.freeWithProduct = this.data.freeWithProduct
      this.formData.image = this.data.image
      this.formData.btnTitle = this.data.btnTitle

  }
}  
</script>


<style>
.modal-mask {
  position: fixed;
  z-index: 9998;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, .5);
  display: table;
  transition: opacity .3s ease;
}
.modal-content {
  width: 1000px !important;
  margin-left: -50%;
}

</style>