const Mongoose = require("mongoose");
const Schema = Mongoose.Schema;

const menu = new Schema({
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
        required: false
    },
    createdAt: {
        type: Date,
        default: Date.now
    },
    products: [{
        type: Schema.Types.ObjectId,
        ref: 'Products',
        required: "Products is required",
    }]
});

module.exports = Mongoose.model("Menu", menu);