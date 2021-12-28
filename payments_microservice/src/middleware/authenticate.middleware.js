const jwt = require('jsonwebtoken');
module.exports.middleware = {
    authenticate: async function (req, res, next) {
        try {
            if (req.headers.authorization && (req.headers.authorization == process.env.SECRET)) {
                next();
            } else {
                res.stats(401).send({
                    message: 'Authentication failed',
                })
            }
        } catch (e) {
            res.status(401).send({
                message: 'Authentication failed',
                success: false
            })
        }
    }
}
