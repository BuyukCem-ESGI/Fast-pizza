<template>
  <div class="col-md-10">
    <h2> Menu </h2>
      <ProductList />
  </div>
</template>

<script>
import UserService from "../services/user.service";
import ProductList from "./ProductList.vue";

export default {
  name: "Menu",
  components: {
      ProductList
  },
  data() {
    return {
      content: "",
      products: [
                {
                    'author': 'David Deustch',
                    'title': 'The beginning of Infinity',
                    'description': 'A book on philosphy about the origins of man',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
                {
                    'author': 'Paul Coelho',
                    'title': 'The Alchemist',
                    'description': 'The true search for one\'s treasure',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
                {
                    'author': 'Susan Kaye Quinn',
                    'title': 'Open Minds',
                    'description': 'Sci-Fi Futuristic book about mind reading',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
                {
                    'author': 'Robert Kiyosaki',
                    'title': 'Rich Dad, Poor Dad',
                    'description': 'Motivational book on wealth building',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
                {
                    'author': 'Dan Brown',
                    'title': 'The Da Vinci Code',
                    'description': 'Conspiracy theories about secrets of the holy grail',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
                {
                    'author': 'Arthur Hailey',
                    'title': 'The money changers',
                    'description': 'Travel back in time and experience the banking system',
                    'img_url': 'https://placeimg.com/640/480/any'
                },
            ]
    };
  },
  provide() {
    return {
      products: this.products
    }
  },
  mounted() {
    UserService.getUserBoard().then(
      (response) => {
        this.content = response.data;
      },
      (error) => {
        this.content =
          (error.response &&
            error.response.data &&
            error.response.data.message) ||
          error.message ||
          error.toString();
      }
    );
  },
};
</script>

