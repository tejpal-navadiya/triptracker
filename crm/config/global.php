<?php

return [
 'superAdminURL' => env('SUPERADMINURL','admin'),
    'businessAdminURL' => env('BUSADMINURL','agency'),
    'default_user_role' => env('DEF_USER_ROLE','Master Admin'),
    'default_user_role_alert_msg' => env('DEF_USER_ROLE_ALERT_MSG','Master Admin role has not been deleted.'),
    'null_object'   => new \stdClass(),
    'default_user_role_assign_alert_msg' => env('DEF_USER_ROLE_ASSIGN_ALERT_MSG','No Master Admin role has been assigned.'),
   
];