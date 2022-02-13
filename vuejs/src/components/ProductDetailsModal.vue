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
                  <div class="row">
                    <div class="col-md-6">
                       <img :src="formData.image"  style="height: 150px;width: 100%">
                    </div>
                    <div class="col-md-3"></div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <h5>{{formData.name}}</h5>
                    </div>
                  </div>
                  <div class="row" v-if="productExist">
                    <div class="col-md-12">
                     <span>Vous avez le choix entre:</span><br>
                     <div  v-for="(item, key) in formData.products" :value="item" :key="key">
                      <span> - {{item.name}}</span>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      {{formData.description}}
                    </div>
                  </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
</template>




<script>
export default {
  name: "ProductDetailsModal",
  props: {
    data: {
        type: Object,
        required: true,
        default: () => {}
    }
  },
    data () {

    return {
      formData: null,
    }

  },
  methods: {
    closeModal() {
      this.$emit('close')
    }
  },
  computed: {
     productExist() {
      return this.formData.products && this.formData.products.length
    }
  },
  created() {
      this.formData = this.data
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
  width: 500px !important;
  margin-left: -25%;
}

</style>