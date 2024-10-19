<?php

return [
    'superAdminURL' => env('SUPERADMINURL','admin'),
    'businessAdminURL' => env('BUSADMINURL','company'),
    'default_user_role' => env('DEF_USER_ROLE','Super Admin'),
    'default_user_role_alert_msg' => env('DEF_USER_ROLE_ALERT_MSG','Master is not the deleted.'),
    'null_object'   => new \stdClass(),

    
];