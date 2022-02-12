<template>
  <div class="row" style="margin-top: 5%">
    <div class="col-md-4"></div>
    <div class="col-md-4"  style="border: 1px solid #bcc1c5; padding: 2%">
      <div class="row">
          <h1>Confirmer la commande</h1>
      </div>
      <div class="row">
        <div class="col-md-12">
          <form>
          <div class="form-group">
          <div class="form-group">
              <label for="exampleInputEmail1">Nom</label>
              <input type="text" v-model="userData.lastName" class="form-control"  placeholder="Veuillez saisir votre nom">
            </div>
              <label for="exampleInputEmail1">Prénom</label>
              <input type="text" v-model="userData.firstName" class="form-control"  placeholder="Veuillez saisir votre prénom">
            </div>
          <div class="form-group">
              <label for="exampleInputEmail1">Mobile</label>
              <input type="number" v-model="userData.phoneNumber" class="form-control"  placeholder="Pour vous contacter si besoin">
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" v-model="userData.email" class="form-control"  placeholder="Pour vous envoyer une confirmation">
            </div>
          </form>
        </div>
      </div>
      <div class="row"></div>
      <div class="row">
        <div class="col-md-12">
          <vue-google-autocomplete
              id="map"
              classname="form-control"
              placeholder="Start typing"
              v-on:placechanged="getAddressData"
              country="fr"
          >
          </vue-google-autocomplete>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12">
          <button v-if="total && total>0" type="submit" @click="paid" class="btn btn-primary" style="width: 100%">Total à payer {{total}}</button>
        </div>
      </div>
    </div>
    <div class="col-md-4"></div>
  </div>
</template>
<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete'
export default {
  name: "cart",
  components: {
    VueGoogleAutocomplete
  },
  props: {
     productData: {

      }
  },
  data() {
    return {
      cart: [],
      address: '',
      userData: {
        firstName: '',
        lastName: '',
        email: '',
        phoneNumber: null
      },
      total: null

    }
  },
  mounted() {
    /*
      this.$store.cart.cartState.forEach(item => {
        this.cart.push(item)
      })*/
  },
  created() {
    let count = 0
    const products =  this.$store.state.cart.products;
    for (const product of products) {
      count+=product.data.price * product.quantity
    }
    this.total = count
    console.log("count", count)
  },

  methods: {
    detail() {
    },
    getAddressData(addressData) {
      this.address = addressData;
    },
    paid() {
      console.log("adress", this.address)
      console.log("user data", this.userData)
    }

  },
};

</script>

<style scoped>
  input {
    width: 100%
  }

</style>
