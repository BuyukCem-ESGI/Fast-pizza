const sendEmail = require('./sendEmail')
const Agenda = require("agenda");
var Agendash = require("agendash");
    const agenda = new Agenda({ db: { 
        address: "mongodb://root:password@mongo-livraison/JobCollections",
        collection : "jobs", 
        options: { useNewUrlParser: true,useUnifiedTopology: true,authSource: "admin" }
      },
      processEvery: "40 seconds"});
    
      agenda.define('send email', async (job) => {
        const { email } = job.attrs.data;
        await sendEmail(email)
    });
    
    agenda.on('ready', () => {
        agenda.start();
        console.log("Agenda is ready !!!!!!!")
    })


 module.exports = (app) => {
        app.post("/jobs", (req, res) => {   
            const body = req.body;   
            agenda.schedule(
              "in 3 minutes",
              "send email",
              req.body
            );
          res.status(201).json("Job added to queue!")
        });
        app.use("/dash", Agendash(agenda));
    }