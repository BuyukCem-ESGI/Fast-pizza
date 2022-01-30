<template>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h2 class="tittle">LOGIN</h2>
        </div>
        <div class="card-body">
          <Form @submit="handleLogin" :validation-schema="schema">
            <div class="form-group">
              <label for="email">Email</label>
              <Field name="email" type="text" class="form-control"/>
              <ErrorMessage name="email" class="error-feedback"/>
            </div>
            <div class="form-group">
              <label for="password">Password</label>
              <Field name="password" type="password" class="form-control"/>
              <ErrorMessage name="password" class="error-feedback"/>
            </div>

            <div class="form-group">
              <button class="btn btn-primary btn-block" :disabled="loading">
                  <span
                      v-show="loading"
                      class="spinner-border spinner-border-sm"
                  ></span>
                <span>Login</span>
              </button>
            </div>

            <div class="form-group">
              <div v-if="message" class="alert alert-danger" role="alert">
                {{ message }}
              </div>
            </div>
          </Form>
        </div>
      </div>
    </div>
    <div class="col-md-4"></div>
  </div>
</template>

<script>
import {Form, Field, ErrorMessage} from "vee-validate";
import * as yup from "yup";

export default {
  name: "Login",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      email: yup.string().required("Email is required!").email("Email is invalid!").max(50, "Must be maximum 50 characters!"),
      password: yup.string().required("Password is required!"),
    });
    return {
      loading: false,
      message: "",
      schema,
    };
  },
  mounted: function () {
    console.log(this)
  },
  computed: {
    loggedIn() {
      return this.$store.state.auth.status.loggedIn;
    },
  },
  created() {
    if (this.loggedIn) {
      this.$router.push("/profile");
    }
  },
  methods: {
    handleLogin(user) {
      this.loading = true;
      this.$store.dispatch("auth/login", user)
      .then((data) => {
        console.log(data);
            this.loading = false;
            this.$router.push("/");
      })
      .catch((error) => {
            this.loading = false;
            this.message = error.message;
          }
      );
    },
  },
};
</script>

<style scoped>
.btn-primary {
  background-color: #1AC073 !important;
  border-color: #1AC073 !important;
  font-weight: bold;
  font-size: 20px
}

.row {
  padding-top: 10vh
}

.card-header {
  background-color: #1AC073
}

.tittle {
  font-weight: bold;
  text-align: center;
  color: #fff;
}
</style>
