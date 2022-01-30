const Mongoose = require("mongoose");
const Schema = Mongoose.Schema;
const ProductSchema = new Schema({
  name: {
    type: String,
    required: true
  },
  description: {
    type: String,
    required: true
  },
  price: [
    {
      taille: String,
      price: Number
    }
  ],
  choices: [
      {
          name: String,
          requiredChoice: Boolean,
          maxChoice: Boolean,
          supplements: [{type:Schema.Types.ObjectId, ref: 'Supplement'}],
      }
  ],
  type: {
    type: String,
    enum: ["Product","Menu"],
    default: "Product"
  },
  imagesUrl: {
      type: String
  }
});

const Product = Mongoose.model("Product", ProductSchema);

module.exports = Product;