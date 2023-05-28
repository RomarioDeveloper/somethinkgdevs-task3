<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link href="Dashboard.css" rel="stylesheet" type="text/css">
    <title>Document</title>
</head>
<body>
    <header id="header">
        <div class="container">
            <div class="header__logo">
                <!-- <a href="../index.html" class=""></a><img src="../img/Group 1.svg"> -->
                <a href="../index.html">
                    <img src="../img/Group 1.svg">
                  </a>
            </div>
        </div>
    </header>
    <main>
    <?php
        // Collecting data from url
                function getWebsiteMetrics($url) {
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_HEADER, true);
                        curl_setopt($ch, CURLOPT_NOBODY, true);
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
                        $response = curl_exec($ch);
                        $info = curl_getinfo($ch);
                        curl_close($ch);
            
                        $loadTime = round($info['total_time'], 2);
                        $fileSize = round($info['download_content_length'] / 1024, 2);
                        $headers = substr($response, 0, $info['header_size']);

                        return [
                            'loadTime' => $loadTime,
                            'fileSize' => $fileSize,
                            'headers' => $headers
                        ];
                    }
                    $url = 'https://gnfd-testnet-fullnode-tendermint-us.nodereal.io';
                    // Using function with nodereal net
                    $metrics = getWebsiteMetrics($url);


                    // Print headers data from nodereal net
                    echo '
                    <p class="someShit">'.$metrics['headers'].'</p>
                    '

                ?>




    </main>
</body>
</html>