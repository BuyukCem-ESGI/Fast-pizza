<template>
  <div id="app">
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-nav">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <a class="navbar-brand" href="#">What2Eat</a>

      <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0 mx-auto">

          <li class="nav-item active">
            <router-link to="/home" class="nav-link">
                Home
            </router-link>
          </li>
          <li class="nav-item active">
            <router-link to="/product-handler" class="nav-link">
                Products
            </router-link>
          </li>
          <li  class="nav-item active">
              <router-link to="/user-orders" class="nav-link">
                My Orders
              </router-link>
          </li>
          <li  class="nav-item active">
              <router-link to="/pizzeria-orders" class="nav-link">
                Orders
              </router-link>
          </li>
          <li  class="nav-item active">
              <router-link to="/cart" class="nav-link">
                Cart
              </router-link>
          </li>
          <li v-if="showAdminBoard" class="nav-item active">
              <router-link to="/admin" class="nav-link">Admin Board</router-link>
          </li>
          <li v-if="showPizzeriaBoard" class="nav-item active">
              <router-link to="/mod" class="nav-link">Pizzeria Board</router-link>
          </li>
        </ul>
        <div v-if="!currentUser" class="navbar-nav ml-auto">
            <li class="nav-item active">
              <router-link to="/register" class="nav-link">
                <font-awesome-icon icon="user-plus" /> Sign Up
              </router-link>
            </li>
            <li class="nav-item active">
              <router-link to="/login" class="nav-link">
                <font-awesome-icon icon="sign-in-alt" /> Login
              </router-link>
            </li>
        </div>
        <div v-if="currentUser" class="navbar-nav ml-auto">
            <li class="nav-item active">
              <router-link to="/profile" class="nav-link">
                <font-awesome-icon icon="user" />
                {{ currentUser.username }}
              </router-link>
            </li>
            <li class="nav-item active">
              <a class="nav-link" @click.prevent="logOut">
                <font-awesome-icon icon="sign-out-alt" /> LogOut
              </a>
            </li>
        </div>
      </div>
    </nav>
    <notifications position="top left"/>
    <div class="container-fluid">
      <router-view />
    </div>
  </div>
</template>

<script>
export default {
  computed: {
    currentUser() {
      return this.$store.state.auth.user;
    },
    showAdminBoard() {
      if (this.currentUser && this.currentUser['roles']) {
        return this.currentUser['roles'].includes('ROLE_ADMIN');
      }

      return false;
    },
    showPizzeriaBoard() {
      if (this.currentUser && this.currentUser['roles']) {
        return this.currentUser['roles'].includes('ROLE_PIZZERIA');
      }

      return false;
    }
  },
  methods: {
    logOut() {
      this.$store.dispatch('auth/logout');
      this.$router.push('/login');
    }
  },
  mounted() {

  }
};
</script>

<style scoped>
.bg-nav {
  background-color: #1AC073 !important
}

.vue-notification-group {
  top: 60px !important;
}
</style>
