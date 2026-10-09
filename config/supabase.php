<?php

// Reads the SUPABASE_* values from .env (only config files should call env())
return [
    'url'    => env('SUPABASE_URL'),
    'key'    => env('SUPABASE_KEY'),
    'bucket' => env('SUPABASE_BUCKET', 'profile-images'),
];