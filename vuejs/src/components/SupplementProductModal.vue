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
                  <SupplementProductForm @clickBtn="closeModal" :data="formData" />
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
</template>




<script>
import SupplementProductForm from './SupplementProductForm.vue';
export default {
  name: "SupplementProductModal",
  components: {
      SupplementProductForm
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
        title: "",
        maxChoice: null,
        value: [],
        requiredChoice: '',
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
      this.formData.title = this.data.name
      this.formData.maxChoice = this.data.maxChoice
      this.formData.value =  this.data.supplements.map((item) => item.name.toLowerCase())
      this.formData.btnTitle = this.data.btnTitle
      this.formData.requiredChoice = this.data.requiredChoice
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