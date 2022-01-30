<template>
    <div class="row header">
      <div class="col-md-1 hide"></div>
      <div class="col-xs-10 col-md-10">
        <div class="row">
          <div class="col-xs-12 col-md-6 header-left">
            <span class="big-text"> Authentic Home food in UK</span><br>
            <span class="small-text">What2Eat is a courier service in which authentic home cook food is delivered to a customer.</span>
            <form class="form-inline search-form">
              <input class="form-control mr-sm-2 input-search" type="search" placeholder="Search" aria-label="Search">
              <button class="btn btn-warning my-2 my-sm-0" type="submit">Search</button>
            </form>
          </div>
          <div class="col-md-6 header-right hide">
            <img src="../assets/cooking.png" />
          </div>
        </div>
      </div>
      <div class="col-md-1 hide"></div>
    </div>
    <div class="row tabs-row">
      <div class="col-md-1"></div>
      <div class="col-md-10">
        <p class="foods-text">Our foods</p>
        <button
          v-for="tab in tabs"
          :key="tab"
          :class="['tab-button', { active: currentTab === tab }]"
          @click="currentTab = tab"
        >
        {{ tab }}
       </button>
      </div>
      <div class="col-md-1"></div>
    </div>
    <div class="row component-row">
      <div class="col-xs-2 col-md-1"></div>
        <!-- Inactive components will be cached! -->
        <keep-alive>
          <component :is="currentTabComponent">     </component>
        </keep-alive>
      <div class="col-xs-2 col-md-1"></div>
    </div>
</template>

<script>
import Menu from "./Menu.vue";
import Pizzas from "./Pizzas.vue";
import Extra from "./Extra.vue";

export default {
  name: "Home",
  data() {
    return {
      content: "",
      currentTab: 'Menu',
      tabs: ['Menu','Pizzas', 'Extra']
    }
  },
  computed: {
    currentTabComponent() {
      return this.currentTab;
    }
  },
  components: {
    Menu,
    Pizzas,
    Extra
  }
};
</script>

<style>
  .header {
    height: 65vh;
    color: red;
    padding: 5rem 0 0 0;
    background-color: #51d27b
  }
  .big-text {
    font-family: Gilroy-Medium;
    font-size: 5vw;
    line-height: 6vw;
    color: #FFFFFF;
  }
  .header-left {
    padding-top: 30px
  }
  .header-right {
  }
  .ctl {
    background-color: blue
  }
  .small-text {
    font-family: SF Pro Text;
    font-style: normal;
    font-weight: normal;
    font-size: 25px;
    line-height: 25px;
    letter-spacing: -0.015em;
    color: #FFFFFF;
    flex: none;
    order: 1;
    flex-grow: 0;
    margin-top: 2rem;
  }
  .btn-warning {
    color: #ffffff !important
  }
  .search-form {
    margin-top: 20px;
  }
  .tabs-row {
    margin-top: 6vw;
  }
  .component-row {
    margin-top: 2vw;
    padding-bottom: 8vw;
  }
  .foods-text {
    font-family: Gilroy-Medium;
    font-size: 34px;
    line-height: 24px;
    letter-spacing: -0.012em;
    color: #000000;
  }
  .tab-button {
    padding: 10px 25px;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
    cursor: pointer;
    margin-bottom: -1px;
    margin-right: -1px;
    border: 1px solid #F3BA00;
    color: #929292;
    font-size: 20px;
    font-weight: bold;
  }
  .tab-button:hover {
    background: #F3BA00;
    color: #000000;
  }
  .tab-button.active {
    background: #F3BA00;
    color: #000000;
  }

  @media only screen and (max-width: 768px) {
  /* For mobile phones: */
  .big-text {
    font-size: 8vw;
    line-height: 9vw;
  }
  .small-text {
    font-size: 16px;
    line-height: 10px;
  }
  .hide {
    display: none
  }
  .header {
    height: 30vh;
    padding: 2rem 0 0 0
  }
  .input-search {
    width: 70% !important;
    margin-right: 5px;
  }
  .search-form {
    margin-top: 15px
  }
    .foods-text {
    font-family: Gilroy-Medium;
    font-size: 28px;
    line-height: 18px;
  }
}
</style>
