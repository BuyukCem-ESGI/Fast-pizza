const AWS = require('aws-sdk');

exports.addImageToBucket = async (file) => {
    if(!file){
        return {
            statusCode: 400,
            body: JSON.stringify({
                message: 'No file provided'
            })
        }
    }
    const base64Data = new Buffer.from(file.replace(/^data:image\/\w+;base64,/, ""), 'base64');
    const type = file.split(';')[0].split('/')[1];

    const params = {
        Bucket: process.env.AWS_BUCKET_NAME,
        Key: randomString(),
        Body: base64Data,
        ContentEncoding: 'base64',
        ContentType: 'image/'+type,
        ACL: 'public-read'
    }

    const s3bucket = new AWS.S3({
        accessKeyId: process.env.AWS_ACCESS_KEY_ID,
        secretAccessKey: process.env.AWS_SECRET_ACCESS_KEY
    });

    return new Promise((resolve,reject) => {
        s3bucket.upload(params, (err, data) => {
            if (err) {
              reject('');
            } else {
                resolve(data.Location);
            }
        });
    });
}
function randomString() {
    length = 80 ;
    const chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"
    let result = '';
    for (let i = length; i > 0; --i) result += chars[Math.round(Math.random() * (chars.length - 1))];
    return result;
}
