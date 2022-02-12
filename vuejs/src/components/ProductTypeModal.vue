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
                        <div class="col-md-10">
                            <label for="name">Name</label><br>
                            <input
                            class="input-width"
                            id="name"
                            v-model="name"
                            type="text"
                            name="name"
                            >
                        </div>
                    </div>
                    <div class="row" style="margin-top: 20px">
                        <div class="col-md-6">
                            <button class="btn btn-success" @click="closeModal">Update</button>
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
  name: "SupplementModal",
  components: {
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
        name: "",
    }

  },
  methods: {
    closeModal() {
        if (this.data === this.name ) {
            this.$emit('close',"")
        }else {
            this.$emit('close',this.name)
        }
      
    }
  },
  created() {
      this.name = this.data

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
}
.input-width {
    width: 100%
}

</style>