# 🔰 BFFS - Backend for Frontend Shield

BFFS is a simple project built with Laravel that implements the backend for frontend pattern to help you build a security shield in front of your APIs and Microservices.

Here are some key points about BFFS:

- Implements the backend for frontend pattern to separate the concerns of the frontend and backend and improve security.
- Includes advanced request validation features, such as E.164 international phone number standard, password NIST standards, email RFC and DNS validation, email spoofing detection, and scanning uploaded files with Cisco ClamAV.
- Uses Redis for rate limiting requests to improve security and reduce the risk of DDoS attacks.
- Utilizes Swoole to speed up response time and improve overall performance.
- Built using Laravel, a popular PHP web application framework, making it easy to integrate with existing systems.

## Features


### Monitoring
- [x] Uptime Monitor
- [x] SSL Certificate Expiry
- [x] Email notification
- [x] Slack notification


### Performance Tuning

- [x] Running on Octane (Swoole or Roadrunner)
- [x] API Aggregation
- [x] Response Caching with Redis


### Security Hardening

- [x] Trusted Hosts
- [x] Add Cloudflare IPs to Trusted Proxies
- [x] CORS Handling
- [x] Rate Limiting with Redis
- [x] Restricting Access by GeoIP2 (MaxMind DB)


### Web Application Firewall (WAF)

- [ ] Bot: Bad Bot Detection
- [ ] RFI: Remote File Inclusion
- [ ] XSS: Cross Site Scripting
- [ ] SQLi: SQL Injection

### Antivirus and Malware
- [x] Scanning uploaded files with Cisco ClamAV


### Advanced Request Validation

- [x] Email RFC compliance
- [x] Email domain DNS 
- [x] Email disposable/throwaway domains
- [x] Email spoofing detection
- [ ] Email deliverability check
- [x] Password NIST standards
- [x] HaveIBeenPwned password check
- [x] Phone country prefix checking and E.164 standard
- [x] Phone number type: mobile, landline, etc
- [ ] Phone number verification


## Getting Started

To get started with BFFS, you will need to have a basic understanding of Laravel and its dependencies.

### Installation

1. Install the package via composer:
```bash
composer create-project moh8med/bffs
```

2. Run the migrations:
```bash
php artisan migrate
```

3. Configure your environment variables in the .env file.

4. Update the databases: 
```bash
# update the disposable domains list
php artisan disposable:update

# retrieves and cache Cloudflare's IP blocks
php artisan cloudflare:reload

# register for a license key at www.maxmind.com
# set your MAXMIND_LICENSE_KEY in .env file
# and update the geoip database
php artisan geoip:update
```

5. Create your first uptime monitor:
```bash
# create your first monitor
php artisan monitor:create https://example.com/

# check the uptime of all sites
php artisan monitor:check-uptime
```

### Usage

Once the server is running, you can start making requests to the endpoints that are protected by the BFFS shield.

1. Start the server:
```bash
php artisan octane:start --port=8001 --watch
```

2. Test your BFFS server:
```bash
curl http://127.0.0.1:8001/todos | jq
```

## Contributions

If you would like to contribute to the project, please feel free to open a pull request with your changes.

## License

This project is licensed under the MIT License.
