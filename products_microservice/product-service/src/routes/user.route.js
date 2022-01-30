const userController = require('../controllers/userController');

module.exports = (app) => {
    app.route('/users/:id')
        .get(userController.getUserById)
        .delete(userController.deleteUser)
        .patch(userController.updateUser)
    app.route('/users')
        .get(userController.getAllUsers)
        .post(userController.createUser)
}