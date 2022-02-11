const Product = require("../models/Product");
const aws = require('../lib/aws')
const AWS = require("aws-sdk");

function respond(respCode, result, res) {
    return res.status(respCode).json(result);
}


exports.createProduct = async (req, res, next) => {
    body = req.body
    let imagesUrl = ""
    if(body.image){
        imagesUrl = await aws.addImageToBucket(body.image)
    }
    const product = await Product.exists({ name: body.name });
    if(product) {
        res.status(400).json({
            message: "Product already exists"
        });
    }else {
        body.imagesUrl = imagesUrl
        const newProduct = new Product(body);
        newProduct
            .save()
            .then((product) => {
                console.log(newProduct);
                res.status(201).json(product);
            })
            .catch((err) => {
                if (err.name === "ValidationError") {
                    console.log("validation error 400");
                    res.status(400).json(err.message);
                } else {
                    console.log("validation error 500");
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
            req.body.imagesUrl = await aws.addImageToBucket(req)
        }
        Product.findOneAndUpdate({_id: req.params.id},req.body ,(err,pro) => {
            if(err) {
               res.status(500).json(err)
            }else {
                res.status(200).json(pro)
            }
        })
    }else {
        res.status(400).json({
            message: "Product not found"
        });
    }
}

exports.deleteProduct = async (req, res, next) => {
    console.log(req.params.id);
    const product = await Product.exists({ _id: req.params.id });
    console.log(product)
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

exports.addImageToBucket = async (req, res, next) => {
    let product = await Product.find({_id: req.params.id}).exec();
    if(product){
        if(req.file) {
            try {
                 let url = await aws.addImageToBucket(req)
            }catch (e) {
                return res.status(400).json({
                    message: "Error uploading image"
                })
            }
        }else {
            return res.status(400).json({
                message: "You must upload an image"
            })
        }
        Product.findOneAndUpdate({_id: req.params.id},{imagesUrl:url} ,(err,pro) => {
            if(err) {
                respond(
                    400,
                    {status: 'err',message: 'Error uploading image'},
                    res
                );
            }else {
                respond(204,pro,res)
            }
        })
    }
}
