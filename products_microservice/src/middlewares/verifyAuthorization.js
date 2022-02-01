const User = require("../models/User");
var basicAuth = require('basic-auth');
const bcrypt = require('bcrypt');

async function verifyAuthorization(req, res, next) {
    const authorization = req.headers["authorization"];
    if (!authorization) {
        res.sendStatus(401);
    } else {
        const [type] = authorization.split(/\s+/);
        if ("Basic" !== type) res.sendStatus(401);
        const credential = basicAuth(req);
        const user = await User.findOne({username : credential.name}).exec();
        if(!user) {
            res.sendStatus(403);
        } else {
            const match = await bcrypt.compare(credential.pass, user.password);
            if(match) {
                req.user = user;
                next();
            } else {
                res.sendStatus(401);
            }
        }
    }
};

async function isAdmin (req, res, next) {
    const user = req.user;
    for (const role of user.roles) {
        if (role === "admin") {
            req.user = user
            next();
            return;
        }
    }
    res.status(403).send({
        message: "Require Admin Role!"
    });
    return;
  };

  module.exports = isAuth = {
    isAdmin: isAdmin,
    verifyAuthorization: verifyAuthorization

  }