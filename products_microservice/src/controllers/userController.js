const User = require("../models/User");
const bcrypt = require('bcrypt');

function respond(respCode, result, res) {
    return res.status(respCode).json(result);
}


exports.createUser = async (req, res, next) => {
    const body = req.body;
    const user = await User.exists({ username: body.username });
    if( user) {
        respond(
            400,
            {status: 'error',message: 'user name already exist'},
            res
        );
    }else {
        body.password = await bcrypt.hash(body.password, await bcrypt.genSalt());
        const newUser = new User(body);
        newUser
          .save()
          .then((user) => {
            res.status(201).json(user);
          })
          .catch((err) => {
            if (err.name === "ValidationError") {
              res.status(400).json(err);
            } else {
              console.error(err);
              res.sendStatus(500);
            }
          });
    }
}

exports.getAllUsers = async (req, res, next) => {
    const users = await User.find({});
    if(users) {
        respond(200,users,res)
    }else {
        respond(
            400,
            {status: 'error',message: 'no user'},
            res
        );
    }
}

exports.getUserById = async (req, res, next) => {
    const user = await User.findById(req.params.id).exec();
    if(user) {
        respond(200,user,res)
    }else {
        respond(
            200,
            {status: 'success',message: 'user not found'},
            res
        );
    }
}

exports.updateUser = async (req, res, next) => {
    const user = await User.exists({ _id: req.params.id });
    if( user) {
        req.body.password = req.body.password ? await bcrypt.hash(req.body.password, await bcrypt.genSalt()) : user.password
        User.findOneAndUpdate({_id: req.params.id},req.body ,(err,findUser) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'user not updated'},
                    res
                );
            }else {
                respond(204,findUser,res)
            }
        }) // executes
    }else {
        respond(
            404,
            {status: 'error',message: 'user not found'},
            res
        );
    }
}

exports.deleteUser = async (req, res, next) => {
    const user = await User.exists({ _id: req.params.id });
    if( user) {
        User.findOneAndRemove(req.params, (err,success) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'user not deleted'},
                    res
                );
            }else {
                respond(200,{status: "success",message: "user deleted"},res)
            }
        }) // executes
    }else {
        respond(
            400,
            {status: 'success',message: 'user not found'},
            res
        );
    }
}