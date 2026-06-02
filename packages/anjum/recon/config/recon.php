<?php

return [
    'enabled'       => (bool) env('RECON_ENABLED', true),
    'log_file'      => storage_path('logs/recon.log'),
    'log_body'      => true,
    'log_headers'   => false,
    'mask_fields'   => ['password', 'password_confirmation', 'token', 'secret'],
    'exclude_paths' => [],
];
