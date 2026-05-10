<?php
if ($_SERVER['REMOTE_ADDR'] === '127.0.0.1' || php_sapi_name() === 'cli') {
    opcache_reset();
    echo 'OPcache cleared';
} else {
    http_response_code(403);
}
