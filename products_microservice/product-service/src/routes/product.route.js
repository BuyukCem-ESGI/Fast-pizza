const productController = require('../controllers/productController');
var multer  = require('multer');

var storage = multer.memoryStorage({
    destination: function(req, file, callback) {
        callback(null, '');
    }
});

let upload = multer({ storage: storage }).single('file');
module.exports = (app) => {
    app.route('/products/:id')
        .get(productController.getProductById)
        .delete(productController.deleteProduct)
        .patch(upload,productController.updateProduct)
    app.route('/products')
        .get(productController.getAllProducts)
        .post(upload,productController.createProduct)
}