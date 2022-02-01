const Product = require("../models/Product");
const aws = require('../lib/aws')

function respond(respCode, result, res) {
    return res.status(respCode).json(result);
}


exports.createProduct = async (req, res, next) => {
    console.log("hello33")
    const body = req.body;
    const product = await Product.exists({ name: body.name }).catch(err => {
        console.log("hello2")
        console.log(err)
    });

    console.log(product)
    if( product) {
        respond(
            400,
            {status: 'error',message: 'product name already exist'},
            res
        );
    }else {
        if (req.file) {
            try {
                req.body.imagesUrl = await aws.addImageToBucket(req)
            }catch (e) {
                return res.status(500).json({
                    message: "Error uploading image"
                })
            }
        }
        const newProduct = new Product(body);
        newProduct
          .save()
          .then((product) => {
              console.log(product);
            res.status(201).json(product);
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

exports.getAllProducts = async (req, res, next) => {
    const products = await Product.find({});
    if(products) {
        respond(200,products,res)
    }else {
        respond(
            400,
            {status: 'error',message: 'no product'},
            res
        );
    }
}

exports.getProductById = async (req, res, next) => {
    const product = await Product.findById(req.params.id).exec();
    if(product) {
        respond(200,product,res)
    }else {
        respond(
            200,
            {status: 'success',message: 'product not found'},
            res
        );
    }
}

exports.updateProduct = async (req, res, next) => {
    const product = await Product.exists({ _id: req.params.id });
    if( product) {
        if (req.file) {
            const imagesUrl = await aws.addImageToBucket(req)
            req.body.imagesUrl = imagesUrl
        }
        Product.findOneAndUpdate({_id: req.params.id},req.body ,(err,pro) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'product not updated'},
                    res
                );
            }else {
                respond(204,pro,res)
            }
        }) // executes
    }else {
        respond(
            404,
            {status: 'error',message: 'product not found'},
            res
        );
    }
}

exports.deleteProduct = async (req, res, next) => {
    const product = await Product.exists({ _id: req.params.id });
    if( product) {
        Product.findOneAndRemove(req.params, (err,success) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'product not deleted'},
                    res
                );
            }else {
                respond(200,{status: "success",message: "product deleted"},res)
            }
        }) // executes
    }else {
        respond(
            400,
            {status: 'success',message: 'product not found'},
            res
        );
    }
}
