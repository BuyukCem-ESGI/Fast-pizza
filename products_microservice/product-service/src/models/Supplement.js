const Mongoose = require("mongoose");
const Schema = Mongoose.Schema;
const SupplementSchema = new Schema({
  name: {
    type: String,
    required: true
  },
  price: {
      type: Number,
      required: true
  },
  freeWithProduct: {
    type: Boolean,
    required: true
  },
  imagesUrl: {
    type: String
}
});

const Supplement = Mongoose.model("Supplement", SupplementSchema);

module.exports = Supplement;