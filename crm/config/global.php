<?php

return [
    'superAdminURL' => env('SUPERADMINURL','admin'),
    'businessAdminURL' => env('BUSADMINURL','agency'),
    'default_user_role' => env('DEF_USER_ROLE','Super Admin'),
    'default_user_role_alert_msg' => env('DEF_USER_ROLE_ALERT_MSG','Super Admin role has not been deleted.'),
    'null_object'   => new \stdClass(),
    'default_user_role_assign_alert_msg' => env('DEF_USER_ROLE_ASSIGN_ALERT_MSG','No Super Admin role has been assigned.'),
    
];