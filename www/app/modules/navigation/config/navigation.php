<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config = array(

    'public'        => array(),
    
    'member'        => array(
        array(
            'label' => lang('settings'),
            'link'  => site_url('ucp/settings'),
        ),
        array(
            'label' => lang('password'),
            'link'  => site_url('ucp/password'),
        ),
    ),
    
    'owner'         => array(
        array(
            'label' => lang('users'),
            'link'  => site_url('acp/user/list'),
        ),
        array(
            'label' => lang('groups'),
            'link'  => site_url('acp/group/list'),
        ),
        array(
            'label' => lang('servers'),
            'link'  => site_url('acp/server/list'),
        ),
        array(
            'label' => lang('settings'),
            'link'  => site_url('acp/settings'),
        )
    )
);