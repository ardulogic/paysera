<?php

return [
    // Allowed appointment lengths in minutes, comma-separated in .env
    'allowed_slot_lengths' => array_map('intval', explode(',', env('APPOINTMENT_ALLOWED_SLOT_LENGTHS', '60'))),
]; 