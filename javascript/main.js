var LOGORA_SECRET = "12345";

function logoraSignon(user) {
    var logoraData = {
      uid: user.uid,
      first_name : user.first_name,
      last_name: user.last_name,
      email: user.email
    };

    var logoraStr = JSON.stringify(logoraData);
    var timestamp = Math.round(+new Date() / 1000);

    /*
     * Note that `Buffer` is part of node.js
     * For pure Javascript or client-side methods of
     * converting to base64, refer to this link:
     * http://stackoverflow.com/questions/246801/how-can-you-encode-a-string-to-base64-in-javascript
     */
    var message = new Buffer(logoraStr).toString('base64');

    /*
     * CryptoJS is required for hashing (included in dir)
     * https://code.google.com/p/crypto-js/
     */
    var result = CryptoJS.HmacSHA1(message + " " + timestamp, LOGORA_SECRET);
    var hexsig = CryptoJS.enc.Hex.stringify(result);

    return {
      remote_auth: message + " " + hexsig + " " + timestamp
    };
}
