const menuController = require('../controllers/menuController');

module.exports = (app) => {
    app.route('/menu/:id')
        .get(menuController.getMenuById)
        .delete(menuController.deleteMenu)
        .patch(menuController.updateMenu)
    app.route('/menu')
        .post(menuController.createMenu)
        .get(menuController.getAllMenu)

}