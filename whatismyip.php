<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>What Is My IP?</title>
    <style type="text/css">
        body {
            margin: 0;
            padding: 0;
            background: #0d1121;
        }

        #main-content {
            margin: 30px;
            text-align: center;
            color: #919191;
        }

        #main-content h1 {
            font: 40px Arial, Helvetica, sans-serif;
        }

        #main-content p {
            font: 24px Arial, serif;
        }

        #main-content p strong {
            font-size: 70px;
            color: #919191;
        }
    </style>
</head>
<body>
<?php

// returns first forwarded IP match it finds
function forwarded_ip()
{
    $keys = array(
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_X_CLUSTER_CLIENT_IP',
    );

    foreach ($keys as $key) {
        if (isset($_SERVER[$key])) {
            $ip_array = explode(',', $_SERVER[$key]);
            foreach ($ip_array as $ip) {
                $ip = trim($ip);
                if (validate_ip($ip)) {
                    return $ip;
                }
            }
        }
    }
    return '';
}

function validate_ip($ip)
{
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false) {
        return false;
    } else {
        return true;
    }
}


$remote_ip = $_SERVER['REMOTE_ADDR'];
$forwarded_ip = forwarded_ip();

?>
<div id="main-content">
    <h1>What Is My IP?</h1>

    <p>The request came from:<br/>
        <strong><?php echo $remote_ip; ?></strong>
    </p>

    <?php if ($forwarded_ip != '') { ?>
        <p>The request was forwarded for:<br/>
            <strong><?php echo $forwarded_ip; ?></strong>
        </p>
    <?php } ?>

</div>
</body>
</html>
