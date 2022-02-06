var AWS = require('aws-sdk');

exports.addImageToBucket = async (req) => {
    const file = req.file;
    const extension = "."+file.mimetype.split("/")[1];
    const filename = Date.parse(new Date())+extension;

    const params = {
        Bucket: process.env.AWS_BUCKET_NAME,
        Key: filename,
        Body: file.buffer,
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
