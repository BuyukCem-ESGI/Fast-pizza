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
    price: {
        type: Number,
        required: true
    },
    typeProduct: {
        type: String,
        required: true
    },
    date_insert: {
        type: Date,
        default: Date.now
    },
    imagesUrl: {
        required: false,
        type: String
    }
});

const Product = Mongoose.model("Product", ProductSchema);

module.exports = Product;
