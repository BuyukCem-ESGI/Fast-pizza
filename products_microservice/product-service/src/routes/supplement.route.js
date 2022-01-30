const supplementController = require('../controllers/supplementController');

var multer  = require('multer');

var storage = multer.memoryStorage({
    destination: function(req, file, callback) {
        callback(null, '');
    }
});

let upload = multer({ storage: storage }).single('file');

module.exports = (app) => {
    app.route('/supplements/:id')
        .get(supplementController.getSupplementById)
        .delete(supplementController.deleteSupplement)
        .patch(upload,supplementController.updateSupplement)
    app.route('/supplements')
        .get(supplementController.getAllSupplements)
        .post(upload,supplementController.createSupplement)
}