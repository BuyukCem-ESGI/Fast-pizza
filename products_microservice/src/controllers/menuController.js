const Menu = require("../models/menu");

exports.getMenuById = async (req, res, next) => {
  const menuId = req.params.menuId;

  try {
    const menu = await Menu.findById(menuId).populate("product");
    if (!menu) {
      return res.status(404).json({
        message: "Menu not found"
      });
    }
    res.status(200).json(menu);

  } catch (err) {
    if (!err.statusCode) {
      err.statusCode = 500;
    }
    next(err);
  }
}

exports.createMenu = async (req, res, next) => {
  const menu = req.body
  const newMenu = new Menu(menu)
  newMenu.save().then(
      Menu.populate(newMenu,{path: "product"})
          .then(result => {
            res.status(201).json({
              message: "Menu created successfully",
              menu: result
            })
          })
  )
}

exports.getAllMenu = async (req, res, next) => {
  try {
    const menu = await Menu.find().populate("product");
    if (!menu) {
      return res.status(404).json({
        message: "Menu not found"
      });
    }
    res.status(200).json(menu);
  } catch (err) {
    if (!err.statusCode) {
      err.statusCode = 500;
    }
    next(err);
  }
}

exports.updateMenu = async (req, res, next) => {
  const menuId = req.params.menuId;
  const menu = req.body;

  try {
    const result = await Menu.findByIdAndUpdate(menuId, menu);
    if (!result) {
      return res.status(404).json({
        message: "Menu not found"
      });
    }
    res.status(200).json({
      message: "Menu updated",
      menu: result
    });
  } catch (err) {
    if (!err.statusCode) {
      err.statusCode = 500;
    }
    next(err);
  }
}

exports.deleteMenu = async (req, res, next) => {
  console.log("here")
  const menuId = req.params.id;
  console.log(menuId)
  Menu.findByIdAndDelete(menuId,function (err, result) {
    if (err) {
      console.log(err)
      return res.status(404).json({
        message: "Menu not found"
      });
    }else{
      console.log(result  )
      res.status(200).json({
        message: "Menu deleted",
        menu: result
      });
    }
  });
}
