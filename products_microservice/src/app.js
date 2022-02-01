require("dotenv").config();
const express = require("express");
const cors = require("cors");
const ip = require('ip');
const ipAddress = ip.address();

require("./lib/mongo");
const userRoute = require('./routes/user.route');
const productRoute = require('./routes/product.route');
const supplementRoute = require('./routes/supplement.route');
const isAuth= require("./middlewares/verifyAuthorization");
const app = express();
const PORT = process.env.PORT || 3000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cors());


app.all('/users')
app.all('/users/*')
userRoute(app)

//app.use(isAuth.verifyAuthorization);
productRoute(app)
supplementRoute(app)

app.get('/', function (req, res) {
    res.send('Hello World!');
  });

app.listen(PORT, () => {
    console.log("Server is running on port " + PORT);
    console.log(`Network access via: ${ipAddress}:${PORT}!`);
});