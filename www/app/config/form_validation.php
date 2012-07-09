<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * This file contains form(s) validation rules
 * @see http://codeigniter.com/user_guide/libraries/form_validation.html
 */

$config = array(
    
    'acp-user-acc-save' => array(
        array(
            'field' => 'user_group',
            'label' => 'Group',
            'rules' => 'trim|required|xss_clean'
        ),
    ),
    
    'acp-user-edit' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'passwd',
            'label' => 'Password',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|xss_clean|valid_email|is_unique[users.email]'
        ),
        array(
            'field' => 'active',
            'label' => 'Account is active ?',
            'rules' => 'trim|xss_clean'
        ),
    ),
    
    'acp-user-add' => array(
        array(
            'field' => 'login',
            'label' => 'Login',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'passwd',
            'label' => 'Password',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|xss_clean|valid_email|is_unique[users.email]'
        ),
        array(
            'field' => 'active',
            'label' => 'Account is active ?',
            'rules' => 'trim|xss_clean'
        ),
    ),
    
    'acp-server-add' => array(
        array(
            'field' => 's-name',
            'label' => 'Name',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 's-address',
            'label' => 'Address',
            'rules' => 'trim|required|xss_clean'
        ),
    ),
    
    'acp-group-edit' => array(
        array(
            'field' => 'g-name',
            'label' => 'Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'g-access',
            'label' => 'Access flags',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'g-public',
            'label' => 'Make this group public ?',
            'rules' => 'trim|xss_clean'
        ),
    ),

    'acp-group-add' => array(
        array(
            'field' => 'g-name',
            'label' => 'Name',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'g-access',
            'label' => 'Access flags',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'g-public',
            'label' => 'Make this group public ?',
            'rules' => 'trim|xss_clean'
        ),
    ),

    'ucp-settings' => array(
        array(
            'field' => 'email',
            'label' => 'Email address',
            'rules' => 'trim|required|xss_clean|valid_email'
        )
    ),
    
    'ucp-password' => array(
        array(
            'field' => 'password_a',
            'label' => 'Current password',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'password_b',
            'label' => 'New password',
            'rules' => 'trim|required|xss_clean|matches[password_c]'
        ),
        array(
            'field' => 'password_c',
            'label' => 'Confirm password',
            'rules' => 'trim|required|xss_clean'
        )
    ), 
    
    'ucp-register' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'email',
            'label' => 'E-mail address',
            'rules' => 'trim|required|xss_clean|valid_email|is_unique[users.email]'
        ),
        array(
            'field' => 'email-conf',
            'label' => 'Confirm e-mail address',
            'rules' => 'trim|valid_email|xss_clean|matches[email]'
        ),
        
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'password-conf',
            'label' => 'Confirm password',
            'rules' => 'trim|xss_clean|matches[password]'
        ),
        array(
            'field' => 'group',
            'label' => 'Group',
            'rules' => 'trim|xss_clean'
        ),
        array(
            'field' => 'server',
            'label' => 'Server',
            'rules' => 'trim|xss_clean'
        ),
    ),
    
    'ucp-login' => array(
        array(
            'field' => 'username',
            'label' => 'Username',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|xss_clean'
        ),
        array(
            'field' => 'remember',
            'label' => 'Remember me',
            'rules' => 'trim|integer'
        )
    ),              
    
    'ucp-recover' => array(
        array(
            'field' => 'email',
            'label' => 'E-mail address',
            'rules' => 'trim|required|xss_clean|valid_email'
        ),
    ),
);
               
/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */