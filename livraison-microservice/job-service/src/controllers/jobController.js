const Job = require("../models/Job");

function respond(respCode, result, res) {
    return res.status(respCode).json(result);
}


exports.createJob = async (req, res, next) => {
    const body = req.body;
    const newJob = new Job(body);
    newJob
        .save()
        .then((job) => {
        res.status(201).json(job);
        })
        .catch((err) => {
        if (err.name === "ValidationError") {
            res.status(400).json(err);
        } else {
            console.error(err);
            res.sendStatus(500);
        }
        });
}

exports.getAllJobs = async (req, res, next) => {
    const jobs = await Job.find({});
    if(jobs) {
        respond(200,jobs,res)
    }else {
        respond(
            400,
            {status: 'error',message: 'no job'},
            res
        );
    }
}

exports.getJobById = async (req, res, next) => {
    const job = await Job.findById(req.params.id).exec();
    if(job) {
        respond(200,job,res)
    }else {
        respond(
            200,
            {status: 'success',message: 'job not found'},
            res
        );
    }
}
