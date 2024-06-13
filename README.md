# ssl-certificates-expiration-checker

## Installation 

git clone https://github.com/Thaldos/ssl-certificates-expiration-checker.git

Chmod 777 expirationDatesByDomain.json file.

Add your domains in domains.json file.

Example of valid domains.json : 

```json
[
    "www.google.com",
    "google.com",
    "https://google.com"
]
```

Don't use "https", or "http" in domains.json :
```json
[
    "https://google.com" 
    // will throw "php_network_getaddresses: getaddrinfo for https failed: Name or service not known"
]
```

## Usage

```PHP
php index.php
```

Find results in expirationDatesByDomain.json file.

Example of results in expirationDatesByDomain.json file :
```json
{
    "www.google.com": "2024-06-23 03:49:55",
    "google.com": "2024-06-23 03:49:55",
    "microsoft.com": "2025-05-06 09:26:41"
}
```