<template>
  <div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="card">
        <div class="card-header">
          <h2 class="tittle">REGISTER</h2>
        </div>
        <div class="card-body">
          <Form @submit="handleRegister" :validation-schema="schema">
            <div v-if="!successful">
              <div class="form-group">
                <label for="firstName">Firstname</label>
                <Field name="firstName" type="text" class="form-control"/>
                <ErrorMessage name="firstName" class="error-feedback"/>
              </div>
              <div class="form-group">
                <label for="lastName">Lastname</label>
                <Field name="lastName" type="text" class="form-control"/>
                <ErrorMessage name="lastName" class="error-feedback"/>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <Field name="email" type="email" class="form-control"/>
                <ErrorMessage name="email" class="error-feedback"/>
              </div>
              <div class="form-group">
                <label for="phoneNumber">Phone number</label>
                <Field name="phoneNumber" type="text" class="form-control"/>
                <ErrorMessage name="phoneNumber" class="error-feedback"/>
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
                  Sign Up
                </button>
              </div>
            </div>
          </Form>
          <div
              v-if="message"
              class="alert"
              :class="successful ? 'alert-success' : 'alert-danger'"
          >
            {{ message }}
          </div>
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
  name: "Register",
  components: {
    Form,
    Field,
    ErrorMessage,
  },
  data() {
    const schema = yup.object().shape({
      firstName: yup
          .string()
          .required("firstName is required!")
          .min(1, "Must be at least 1 characters!")
          .max(100, "Must be maximum 100 characters!"),
      lastName: yup
          .string()
          .required("lastName is required!")
          .min(1, "Must be at least 1 characters!")
          .max(100, "Must be maximum 100 characters!"),
      email: yup
          .string()
          .required("Email is required!")
          .email("Email is invalid!")
          .max(50, "Must be maximum 50 characters!"),
      phoneNumber: yup
          .string()
          .required("Phone number is required!")
          .min(10, "Must be at least 10 characters!")
          .max(10, "Must be maximum 10 characters!"),
      password: yup
          .string()
          .required("Password is required!")
          .min(6, "Must be at least 6 characters!")
          .max(40, "Must be maximum 40 characters!"),
    });
    return {
      successful: false,
      loading: false,
      message: "",
      schema,
    };
  },
  computed: {
    loggedIn() {
      return this.$store.state.auth.status.loggedIn;
    },
  },
  mounted() {
    if (this.loggedIn) {
      this.$router.push("/profile");
    }
  },
  methods: {
    handleRegister(user) {
      this.message = "";
      this.successful = false;
      this.loading = true;
      this.$store.dispatch("auth/register", user).then(
          (data) => {
            this.loading = false;
            this.$router.push({
              path: '/login',
              params: {
                message: data,
                successful: true
              }
            });
          },
          (error) => {
            this.message = error;
            this.successful = false;
            this.loading = false;
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
