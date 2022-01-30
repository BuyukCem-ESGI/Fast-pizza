<template>
  <div class="row">
  <div class="col-md-8">
    <h4>Size of the pizza</h4><br>
  </div>
  </div>
  <div class="row">
    <div class="col-md-3">
      <label>Size</label>
      <input v-model="taille" />
    </div>
    <div class="col-md-3">
      <label>Price</label>
      <input v-model="price" type="number"/>
    </div>
    <div class="col-md-2">
       <button style="margin-top: 25px" class="btn btn-success" @click="addNew">Add</button>
    </div>
  </div>
  <div class="row" style="margin-top: 20px">
    <div class="col-md-5">
      <ul v-for="(todo, index) in todos" :key="todo.id">
      <li>
        <div
          :contenteditable="todo.edit"
          @click.stop="
            !todos[index].edit && (todos[index].edit = !todos[index].edit)
          "
          @keydown.enter.prevent="
            (event) => (todos[index].taille = event.target.textContent)
          "
        >
          <span>{{ todo.taille }} : {{todo.price}} €</span>
          <span><button class="btn btn-ligth" @click="todos.splice(index, 1)"><font-awesome-icon icon="times" /></button></span>
        </div>
        
      </li>
    </ul>
    </div>
  </div>
</template>

<script>

export default {
  name: "TypeList",
  inject: ['tailles'],
  data: () => ({
    taille: "",
    price: null,
    todos: [],
  }),
  created() {
      this.todos = this.tailles
  },
  methods: {
      addNew() {
        if( this.taille != "" && parseInt(this.price) ) {
          this.todos.push({
            taille: this.taille,
            price: this.price
          });
          this.$emit('childToParent', this.todos)
          this.taille = "";
          this.price = "";
        }
      },
  }
};
</script>

<style>
</style>