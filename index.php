<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
    <link href="style.css" rel="stylesheet" type="text/css">
    <meta http-equiv="Refresh" content="1" />
    <title>Dashboard</title>
</head>
<body>
    <header id="header">
        <div class="container">
            <div class="header__logo">
                <img src="img/Group 1.svg">
            </div>

            <div class="button__Dashboard">
                <a href="Dashboard/Dashboard.php">Dashboard</a>
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


        // Creating new DOM from url
        $html = file_get_contents('https://gnfd-testnet-fullnode-tendermint-us.nodereal.io/abci_info?');
        $dom = new DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors(false);
        $xpath = new DOMXPath($dom);

        // Taking all data from <p> tags
        $paragraphs = $xpath->query('//p');

        foreach ($paragraphs as $paragraph) {
            $string = $paragraph->nodeValue;
        }
        // Save load time info
        $kow = 0;
        for ($i = 0; $kow <= 22; $i++) {
            if ($string[$i] == '"' and $kow == 20) {
                $startIndex = $i+1;
            } else if ($string[$i] == '"' and $kow == 22) {
                $endIndex = $i - 2;
            }
            if ($string[$i] == '"') {
                $kow++;
            }
        }
        $res = substr($string, $startIndex, $endIndex - $startIndex);


        // Ping nodereal net
        function ping($host) {
            $output = '';
            exec("ping -c 4 " . escapeshellarg($host), $output);
            return implode("\n", $output);
        }
        $hostname = 'https://gnfd-testnet-fullnode-tendermint-us.nodereal.io';
        $result = ping($hostname);



        // Print collected data
        echo '
        <p class="someShit"> Load Time - '.$metrics['loadTime'].' second</p>
        <p class="someShit"> Height - '.$res.'</p>
        <p class="someShit">'.$metrics['headers'].'</p>
        ';
?>
    </main>
</body>
</html>
