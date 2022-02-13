const jobController = require('../controllers/jobController');
module.exports = (app) => {
    app.route('/jobs/:id')
        .get(jobController.getJobById)
    app.route('/jobs')
        .get(jobController.getAllJobs)
}