const Mongoose = require("mongoose");
const Schema = Mongoose.Schema;
const UserSchema = new Schema({
    username: {type: String,required: true},
    password: {type: String, required: true},
    roles: [String]
});

const User = Mongoose.model("User", UserSchema);
module.exports = User;