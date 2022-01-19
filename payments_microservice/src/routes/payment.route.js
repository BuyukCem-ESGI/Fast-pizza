const paymentController = require('../controllers/payment.controller')
import {middleware} from '../middleware/authenticate.middleware';

module.exports = (app) => {
    app.route('/customer')
        .post(middleware.authenticate, paymentController.newCustomer)
    app.route('/card')
        .get(middleware.authenticate, paymentController.getAllCard)
        .post(middleware.authenticate, paymentController.addNewCard)
        .delete(middleware.authenticate, paymentController.deleteCard)
    app.route('/intents/:customer_id')
        .post(middleware.authenticate, paymentController.createIntentsChargeWithCustomerId)

    app.route('/intents')
        .post(middleware.authenticate, paymentController.createIntentsWithoutSavedCard)
    app.route('/intents/confirme')
        .post(middleware.authenticate, paymentController.confirmeIntentsPayment)
}
