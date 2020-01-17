<?php
define('LOGORA_SECRET_KEY', '123456');
define('LOGORA_PUBLIC_KEY', 'abcdef');

$data = array(
        "uid" => $user["uid"],
        'first_name'=> $user['first_name'],
        'last_name'=> $user['last_name'],
        "email" => $user["email"]
    );

function dsq_hmacsha1($data, $key) {
    $blocksize=64;
    $hashfunc='sha1';
    if (strlen($key)>$blocksize)
        $key=pack('H*', $hashfunc($key));
    $key=str_pad($key,$blocksize,chr(0x00));
    $ipad=str_repeat(chr(0x36),$blocksize);
    $opad=str_repeat(chr(0x5c),$blocksize);
    $hmac = pack(
                'H*',$hashfunc(
                    ($key^$opad).pack(
                        'H*',$hashfunc(
                            ($key^$ipad).$data
                        )
                    )
                )
            );
    return bin2hex($hmac);
}

$message = base64_encode(json_encode($data));
$timestamp = time();
$hmac = dsq_hmacsha1($message . ' ' . $timestamp, LOGORA_SECRET_KEY);
?>
<script type="text/javascript">
var logora_config = function() {
    this.page.remote_auth_s3 = "<?php echo "$message $hmac $timestamp"; ?>";
    this.page.api_key = "<?php echo LOGORA_PUBLIC_KEY; ?>";
}
</script>
