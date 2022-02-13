var nodemailer = require('nodemailer');

async function sendMail(email){
  
return new Promise((resolve,reject)=>{
var transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: '',
    pass: ''
  }
});
var mailOptions = {
  from: '',
  to: email,
  subject: "Livraison What2Eat" ,
  text: "Votre commande vient d'être livré"
};

transporter.sendMail(mailOptions, function(error, info){
  if (error) {
    console.log("error ",error)
   reject(false);
  } else {
    console.log("info ",info)
   resolve(true);
  }
});
})
}

module.exports=sendMail