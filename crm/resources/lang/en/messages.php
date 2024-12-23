<?php

return [
    'admin' => [
        'plan' => [
            'add_plan_success' => 'Plan created successfully.',
            'edit_plan_success' => 'Plan updated successfully.',
            'delete_plan_success' => 'Plan deleted successfully.',
            'roll_plan_access_success' => 'Plan access updated successfully.'
        ],
    ],
    'masteradmin' => [
        'forgot-password' => [
            'link_send_success' => 'We have emailed your password reset link.',
            'link_send_error' => 'Failed to send password reset email.',
            'send_success' => 'Your password has been reset.',
            'send_error' => 'Invalid token!',
        ],
        'business-profile' => [
            'send_success' => 'Business Profile Updated successfully.',
        ],
        'user-role' => [
            'add_role_success' => 'User role created successfully.',
            'delete_role_success' => 'User role deleted successfully.',
            'edit_role_success' => 'User role updated successfully.',
            'roll_user_access_success' => 'User access updated successfully.'
        ],
        'sales-tax' => [
            'send_success' => 'SalesTax created successfully.',
            'edit_sales_success' => 'sales updated successfully',
            'delete_sales_success' => 'sales deleted successfully',
        ],
        'user' => [
            'send_success' => 'User created successfully.',
            'edit_user_success' => 'User updated successfully',
            'delete_user_success' => 'User deleted successfully',
            'link_send_success' => 'Success! A user information details has been sent to user.',
            'link_send_error' => 'Failed to update.',
            'not_authenticated' => 'This user is not authenticated.',
            
            
        ],

        // add by dx....
        'email-template' => [
            'send_success' => 'Email Template created successfully.',
            'edit_emailtemplate_success' => 'Email Template updated successfully',
            'delete_emailtemplate_success' => 'Email Template deleted successfully',
        ],



        // end.....
        'register' => [
            'link_send_success' => 'Success! A registration email has been sent to you.',
            'link_send_error' => 'Failed to send email.',
        ],
        'sales-customers' => [
            'send_success' => 'Customer created successfully.',
            'edit_salescustomers_success' => 'Customer updated successfully',
            'delete_salescustomers_success' => 'Customer deleted successfully',
        ],
        'sales-product' => [
            'send_success' => 'Product created successfully.',
            'edit_salesproduct_success' => 'Product updated successfully',
            'delete_salesproduct_success' => 'Product deleted successfully',
        ],
        'estimate' => [
            'send_success'  => 'Estimate created successfully!',
            'edit_success' => 'Estimate updated successfully.',
            'delete_success' => 'Estimate deleted successfully.',
            
        ],
        'purchases-product' => [
            'send_success' => 'Product created successfully.',
            'edit_purchasesproduct_success' => 'Product updated successfully',
            'delete_purchasesproduct_success' => 'Product deleted successfully',
        ],
        'purchases-vendor' => [
            'send_success' => 'Vendor created successfully.',
            'edit_purchasesvendor_success' => 'Vendor updated successfully',
            'delete_purchasesvendor_success' => 'Vendor deleted successfully',
            'add_bankdetail_success' => 'Bank detail added successfully',
        ],
        'invoice' => [
            'send_success'  => 'Invoice created successfully!',
            'edit_success' => 'Invoice updated successfully.',
            'delete_success' => 'Invoice deleted successfully.',
            
        ],
        'chart-of-account' => [
            'send_success' => 'account created successfully.',
            'edit_account_success' => 'account updated successfully',
            'delete_account_success' => 'account deleted successfully',
            
        ],
        're-invoice' => [
            'edit_success' => 'Recurring Invoice updated successfully.',
            
        ],
        'bill' => [
            'send_success' => 'Bill created successfully!.',
            'edit_success' => 'Bill updated successfully.',
        ],

    ],
    // API messages

    'api' => [
        'authentication_err_message'    => 'Authentication token has expired.',
        'logout'                        => 'Account logged out successfully.',
        'user'  => [
            'user_get_profile_success' => 'User profile get successfully.',
            'user_not_found' => 'User not found.',
            'profile_setup_success' => 'profile updated successfully.',
            'password_change_success' => 'Change Password updated successfully.',
            'register_success' => 'User register successfully.',
            'invalid' =>'Invalid input',
            'login_success' => 'Login successfully',
        ],
        'country'=> [
            'country_get_success' => 'Country data get successfully.',
        ],
        'state'=> [
            'state_get_success' => 'State data get successfully.',
        ],
        'city'=> [
            'city_get_success' => 'City data get successfully.',
        ],
        'subscription_plans'=> [
            'plan_get_success' => 'Subscription Plans data get successfully.',
        ],
        'trip' => [
            'list_success' => 'Trip data get successfully.',
            'status_list_success' => 'Trip Status data get successfully.',
        ],
        'task' => [
            'list_success' => 'Task data get successfully.',
            'status_list_success' => 'Task Status data get successfully.',
        ],
        'agent' => [
            'list_success' => 'Agency data get successfully.',
        ]
    ]
    
];