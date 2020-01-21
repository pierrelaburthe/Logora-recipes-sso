import base64
import hashlib
import hmac
import simplejson
import time

LOGORA_SECRET_KEY = '123456'

def get_logora_sso(user):
    # create a JSON packet of our data attributes
    data = simplejson.dumps({
        'uid': user['uid'],
        'first_name': user['first_name'],
        'last_name': user['last_name'],
        'email': user['email'],
    })
    # encode the data to base64
    message = base64.b64encode(data)
    # generate a timestamp for signing the message
    timestamp = int(time.time())
    # generate our hmac signature
    sig = hmac.HMAC(LOGORA_SECRET_KEY, '%s %s' % (message, timestamp), hashlib.sha1).hexdigest()

# return a script tag to insert the sso message
    return """<script type="text/javascript">
    var logora_config = {
        remote_auth: "%(message)s %(sig)s %(timestamp)s";
    }
    </script>""" % dict(
        message=message,
        timestamp=timestamp,
        sig=sig,
        pub_key=LOGORA_PUBLIC_KEY,
    )
