const Mongoose = require("mongoose");

Mongoose.connect(process.env.MONGO_URL, {
  useNewUrlParser: true,
  authSource: "admin",
  connectTimeoutMS: 500,
})
  .then(() => {
    console.log("Connected to MongoDB Livraison");
  })
  .catch((err) => {
    console.log("Error connecting to MongoDB: ", err);
  });

module.exports = Mongoose.connection;