const Mongoose = require("mongoose");
const Schema = Mongoose.Schema;
const JobSchema = new Schema({
  email: {
    type: String,
    required: true
  },
  orderId: {
    type: String,
    required: true
  }
});

const Job = Mongoose.model("Job", JobSchema);

module.exports = Job;