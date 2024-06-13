<?php
/**
 * Return the SSL certificate expiration date for given domain.
 * Examples for $domain value :
 *      google.com
 *      www.google.com
 */
function getCertificateExpirationDate(string $domain): \DateTime
{
    $certificateExpirationDate = new DateTime('2000-01-01 00:00:00');

    // Get connection to domain :
    $streamContext = stream_context_create([
        "ssl" => [
            "capture_peer_cert" => true,
            "verify_peer" => false,
            "verify_peer_name" => false,
        ],
    ]);
    $client = @stream_socket_client(
        "ssl://{$domain}:443",
        $errno,
        $errstr,
        30,
        STREAM_CLIENT_CONNECT,
        $streamContext
    );
    if ($client !== false) {
        $params = stream_context_get_params($client);
        if (!empty($params['options']['ssl']['peer_certificate'])) {
            $cert = openssl_x509_parse($params['options']['ssl']['peer_certificate']);
            if ($cert !== false) {
                $validToTimestamp = $cert['validTo_time_t'];
                $certificateExpirationDate->setTimestamp($validToTimestamp);
            } else {
                echo "Cannot parse certificat of " . $domain . "\n";
            }
        } else {
            echo "Cannot find certificat of " . $domain . "\n";
        }
    } else {
        echo $errstr . "\n";
    }

    return $certificateExpirationDate;
}


// Get the list of domains to check :
$domainsJson = file_get_contents('domains.json');
$domains = json_decode($domainsJson, true);
if (!empty($domains)) {

}

// Get the SSL certificate expiration date for each domains :
$expirationDatesByDomain = [];
foreach ($domains as $domain) {
    $expirationDateTime = getCertificateExpirationDate($domain);
    $expirationDate = $expirationDateTime->format('Y-m-d H:i:s');
    $expirationDatesByDomain[$domain] = $expirationDate;
}

// Sort by expiration dates :
asort($expirationDatesByDomain);

// Display in terminal :
foreach ($expirationDatesByDomain as $domain => $expirationDate) {
    echo $expirationDate . " " . $domain . "\n";
}

$resultsJson = json_encode($expirationDatesByDomain, JSON_PRETTY_PRINT);
file_put_contents('expirationDatesByDomain.json', $resultsJson);
