require 'rubygems'
require 'base64'
require 'cgi'
require 'openssl'
require "json"

LOGORA_SECRET_KEY = '<YOUR_SECRET_KEY>'

def get_logora_sso(user)
    # create a JSON packet of our data attributes
    data =  {
      'uid' => user['uid'],
      'first_name' => user['first_name'],
      'last_name' => user['last_name'],
      'email' => user['email']
    #'avatar' => user['avatar'],
    #'url' => user['url']
    }.to_json

    # encode the data to base64
    message  = Base64.encode64(data).gsub("\n", "")
    # generate a timestamp for signing the message
    timestamp = Time.now.to_i
    # generate our hmac signature
    sig = OpenSSL::HMAC.hexdigest('sha1', LOGORA_SECRET_KEY, '%s %s' % [message, timestamp])

    # return a script tag to insert the sso message
    return "<script type=\"text/javascript\">
        var logora_config = {
            remote_auth: \"#{message} #{sig} #{timestamp}\"
        }
  </script>"
end
