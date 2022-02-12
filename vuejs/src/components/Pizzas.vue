<template>
  <div class="col-md-8">
    <h2> Pizzas </h2>
      <ProductList />
  </div>
</template>

<script>
import ProductList from "./ProductList.vue";
import ProductService from '../services/product.service'

export default {
  name: "Pizzas",
  components: {
      ProductList
  },
  data() {
    return {
      content: "",
      products: [
    {
        _id: "1",
        name: "type 1",
        description: "description 1",
        price: 12,
        image: "https://myvam.s3.eu-west-3.amazonaws.com/e1b3xDT7Ev7XbCYrnYSfBMXlUOS6YJBHJERbzcrVSmL8rG8yDg6FKLvNEPD3KqStgXc7GOEN6j6oYhnt"
    },

    {_id: "2",
        name: "type 2",
        description: "description 2",
        price: 12,
        image: "https://myvam.s3.eu-west-3.amazonaws.com/e1b3xDT7Ev7XbCYrnYSfBMXlUOS6YJBHJERbzcrVSmL8rG8yDg6FKLvNEPD3KqStgXc7GOEN6j6oYhnt"
    },
    {_id: "3",
        name: "type 3",
        description: "description 3",
        price: 12,
        image: "https://myvam.s3.eu-west-3.amazonaws.com/e1b3xDT7Ev7XbCYrnYSfBMXlUOS6YJBHJERbzcrVSmL8rG8yDg6FKLvNEPD3KqStgXc7GOEN6j6oYhnt"
    },
    {
       _id: "4",
        name: "type 4",
        description: "description 4",
        price: 12,
        image: "https://myvam.s3.eu-west-3.amazonaws.com/e1b3xDT7Ev7XbCYrnYSfBMXlUOS6YJBHJERbzcrVSmL8rG8yDg6FKLvNEPD3KqStgXc7GOEN6j6oYhnt"
    },
    {_id: "5",
        name: "type 5",
        description: "description 5",
        price: 12,
        image: "https://myvam.s3.eu-west-3.amazonaws.com/e1b3xDT7Ev7XbCYrnYSfBMXlUOS6YJBHJERbzcrVSmL8rG8yDg6FKLvNEPD3KqStgXc7GOEN6j6oYhnt"
    }
]
    };
  },
  provide() {
    return {
      products: this.products
    }
  },
  mounted() {
    try{
          ProductService.getAllProducts().then((response) => {
              this.products = response.data
          })
      }catch(e) {
          console.log(e)
      }
  },
};
</script>

