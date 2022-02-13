require("dotenv").config();
const express = require("express");
const cors = require("cors");
const ip = require('ip');
const ipAddress = ip.address();
require("./lib/mongo");

const jobRoute = require('./routes/job.route');
const agendaInit = require('./lib/defineAgenda')

const app = express();
const PORT = process.env.PORT || 6000;

app.use(express.json());
app.use(express.urlencoded({ extended: true }));
app.use(cors());

jobRoute(app)
agendaInit(app)

app.get('/', function (req, res) {
    res.send('Hello World!');
  });

app.listen(PORT, () => {
    console.log("Livraison Server is running on port " + PORT);
    console.log(`Network access via: ${ipAddress}:${PORT}!`);
});