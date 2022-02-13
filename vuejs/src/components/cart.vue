<template>
  <div class="row" style="margin-top: 5%">
    <div class="col-md-4"></div>
    <div class="col-md-4"  style="border: 1px solid #bcc1c5; padding: 2%">
      <div class="row">
          <h1>Confirmer la commande</h1>
      </div>
      <div class="row">
      <h4>Information personnelle</h4>
        <div class="col-md-12">
          <div class="form-group">
          <div class="form-group">
              <label for="exampleInputEmail1">Nom</label>
              <input type="text" v-model="userData.lastName" class="form-control"  placeholder="Veuillez saisir votre nom" >
               <div v-if="errors.lastName" class="error">
                <small>{{ errors.lastName }}</small>
              </div>
            </div>
              <label for="exampleInputEmail1">Prénom</label>
              <input type="text" v-model="userData.firstName" class="form-control"  placeholder="Veuillez saisir votre prénom">
               <div v-if="errors.firstName" class="error">
                <small>{{ errors.firstName }}</small>
              </div>
            </div>
          <div class="form-group">
              <label for="exampleInputEmail1">Mobile</label>
              <input type="number" v-model="userData.phoneNumber" class="form-control"  placeholder="Pour vous contacter si besoin">
               <div v-if="errors.phoneNumber" class="error">
              <small>{{ errors.phoneNumber }}</small>
            </div>
            </div>
            <div class="form-group">
              <label for="exampleInputEmail1">Email address</label>
              <input type="email" v-model="userData.email" class="form-control"  placeholder="Pour vous envoyer une confirmation">
               <div v-if="errors.email" class="error">
              <small>{{ errors.email }}</small>
            </div>
            </div>
        </div>
      </div>
      <div class="row"></div>
      <div class="row">
        <div class="col-md-12">
        <h4>Adresse</h4>
          <vue-google-autocomplete
              id="map"
              classname="form-control"
              placeholder="Start typing"
              v-on:placechanged="getAddressData"
              country="fr"
          >
          </vue-google-autocomplete>
          <div v-if="errors.address" class="error">
              <small>{{ errors.address }}</small>
            </div>
        </div>
      </div>
      <div class="row" style="margin-top: 20px">
        <div class="col-md-12">
        <h4>Moyen de paiement</h4>
          <div class="form-group">
            <label>Card number</label>
            <input :data-error="(errors.cardNumber)?true:false" v-model="cardNumber" type="tel" class="form-control" placeholder="#### #### #### ####" v-cardformat:formatCardNumber @change="validate">
            <div v-if="errors.cardNumber" class="error">
              <small>{{ errors.cardNumber }}</small>
            </div>
          </div>
          <div class="form-group">
            <label>Card Expiry</label>
            <input :data-error="(errors.cardExpiry)?true:false" v-model="cardExpiry" maxlength="10" class="form-control" v-cardformat:formatCardExpiry @change="validate">
            <div v-if="errors.cardExpiry" class="error">
              <small>{{ errors.cardExpiry }}</small>
            </div>
          </div>
          <div class="form-group">
            <label>Card CVC</label>
            <input :data-error="(errors.cardCvc)?true:false" v-model="cardCvc" class="form-control" v-cardformat:formatCardCVC @change="validate">
            <div v-if="errors.cardCvc" class="error">
              <small>{{ errors.cardCvc }}</small>
            </div>
          </div>
        </div>
        </div>
      <div class="row">
        <div class="col-md-12">
          <button v-if="total && total>0" type="submit" @click="paid" class="btn btn-primary" style="width: 100%">Total à payer {{total}} €</button>
        </div>
      </div>
    </div>
    <div class="col-md-4"></div>
  </div>
</template>
<script>
import VueGoogleAutocomplete from 'vue-google-autocomplete'
import OrderService from '../services/order.service'
import { notify } from "@kyvg/vue3-notification";
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
      total: null,
      cardNumber: null,
      cardExpiry: null,
      cardCvc: null,
      errors: {},

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
  },

  methods: {
    detail() {
      
    },
    getAddressData(addressData) {
      this.address = addressData;
    },
    paid() {
      this.validate()
      if(Object.keys(this.errors).length === 0) {
        const objectData = {
          adress: this.address,
          userData: this.userData,
          order: this.$store.state.cart.products,
          card: {
            cardNumber: this.cardNumber,
            cardExpMonth: this.cardExpiry.split("/")[0].trim(),
            cardExpYear: this.cardExpiry.split("/")[1].trim(),
            cardCvv: this.cardCvc,
          },
          total: this.total
        }
        OrderService.addOrder(objectData).then(() => {
          this.$store.dispatch('cart/clearCart')
          this.$router.push('/user-orders')
          notify({
            title: "SUCCESS",
            text: "Votre commande a été validée",
            type: 'success',
          });
        }).catch(() => {
          notify({
            type: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue lors de la commande'
          })
        })
      }
    },
    validate (){
      console.log("control")
          // init
          this.errors = {};
          const  reg = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(.\w{2,3})+$/
          // validate card number
          if(!this.$cardFormat.validateCardNumber(this.cardNumber)){
            this.errors.cardNumber = "Invalid Credit Card Number.";
          }

          // validate card expiry
          if (!this.$cardFormat.validateCardExpiry(this.cardExpiry)) {
            this.errors.cardExpiry = "Invalid Expiration Date.";
          }

          // validate card CVC
          if (!this.$cardFormat.validateCardCVC(this.cardCvc)) {
            this.errors.cardCvc = "Invalid CVC.";
          }

          if(this.userData.lastName.length <= 0 ) {
            this.errors.lastName = "Invalid lastname"
          }

          if(this.userData.firstName.length <= 0 ) {
            this.errors.firstName = "Invalid firstname"
          }

          if(!reg.test(this.userData.email)) {
            this.errors.email = "Invalide email"
          }

          if (Object.keys(this.address).length === 0) {
            this.errors.address = "Invalid address"
          }

        },
  },
};

</script>

<style scoped>
  input {
    width: 100%
  }
  input[data-error="true"]{
        border-color: #dc3545;
        padding-right: calc(1.5em + .75rem);
        background-image: url(data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='%23dc354…%3e%3ccircle cy='3' r='.5'/%3e%3ccircle cx='3' cy='3' r='.5'/%3e%3c/svg%3E);
        background-repeat: no-repeat;
        background-position: center right calc(.375em + .1875rem);
        background-size: calc(.75em + .375rem) calc(.75em + .375rem);
      }
  .card-number{
    padding-right: 20px;
    font-family: SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;
    font-size: .9rem;
    cursor: pointer;
  }
  .error {
    color: red
  }

  .center-text {
    text-align: center
  }

</style>
