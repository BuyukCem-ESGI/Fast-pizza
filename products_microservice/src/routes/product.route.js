const productController = require('../controllers/productController');

let multer  = require('multer');

let storage = multer.memoryStorage({
    destination: function(req, file, callback) {
        callback(null, '');
    }
});

let upload = multer({ storage: storage }).single('file');
module.exports = (app) => {
    app.route('/products/:id')
        .get(productController.getProductById)
        .delete(productController.deleteProduct)
        .patch(productController.updateProduct)
    app.route('/products')
        .get(productController.getAllProducts)
        .post(upload,productController.createProduct)
}
