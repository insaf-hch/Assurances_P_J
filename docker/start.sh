@'
#!/bin/bash
php-fpm -D
nginx -g "daemon off;"
'@ | Set-Content docker/start.sh -Encoding UTF8