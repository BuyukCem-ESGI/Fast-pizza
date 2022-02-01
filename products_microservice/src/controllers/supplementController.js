const  Supplement = require("../models/Supplement");
const aws = require('../lib/aws')

function respond(respCode, result, res) {
    return res.status(respCode).json(result);
}


exports.createSupplement = async (req, res, next) => {
    const body = req.body;
    const imagesUrl = await aws.addImageToBucket(req)
    const supplement = await Supplement.exists({ name: body.name });
    if( supplement) {
        respond(
            400,
            {status: 'error',message: 'supplement name already exist'},
            res
        );
    }else {
        body.imagesUrl = imagesUrl
        const newProduct = new Supplement(body);
        newProduct
          .save()
          .then((supplement) => {
            res.status(201).json(supplement);
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

exports.getAllSupplements = async (req, res, next) => {
    const products = await Supplement.find({});
    if(products) {
        respond(200,products,res)
    }else {
        respond(
            400,
            {status: 'error',message: 'no supplement'},
            res
        );
    }
}

exports.getSupplementById = async (req, res, next) => {
    const supplement = await Supplement.findById(req.params.id).exec();
    if(supplement) {
        respond(200,supplement,res)
    }else {
        respond(
            200,
            {status: 'success',message: 'supplement not found'},
            res
        );
    }
}

exports.updateSupplement = async (req, res, next) => {
    const supplement = await Supplement.exists({ _id: req.params.id });
    if( supplement) {
        if (req.file) {
            const imagesUrl = await aws.addImageToBucket(req)
            req.body.imagesUrl = imagesUrl
        }
        Supplement.findOneAndUpdate({_id: req.params.id},req.body ,(err,supplement) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'supplement not updated'},
                    res
                );
            }else {
                respond(204,supplement,res)
            }
        }) // executes
    }else {
        respond(
            404,
            {status: 'error',message: 'supplement not found'},
            res
        );
    }
}

exports.deleteSupplement = async (req, res, next) => {
    const supplement = await Supplement.exists({ _id: req.params.id });
    if( supplement) {
        Supplement.findOneAndRemove({_id: req.params.id}, (err,success) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'supplement not deleted'},
                    res
                );
            }else {
                respond(200,{status: "success",message: "supplement deleted"},res)
            }
        }) // executes
    }else {
        respond(
            400,
            {status: 'success',message: 'supplement not found'},
            res
        );
    }
}