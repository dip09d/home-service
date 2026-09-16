<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['first_tag_open'] = '<li class="page-item">';
$config['first_tag_close'] = '</li>';
$config['last_tag_open'] = '<li class="page-item">';
$config['last_tag_close'] = '</li>';
$config['next_tag_open'] = '<li class="page-item">';
$config['next_tag_close'] = '</li>';
$config['prev_tag_open'] = '<li class="page-item">';
$config['prev_tag_close'] = '</li>';
$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="javascript:void(0)">';
$config['cur_tag_close'] = '</a></li>';
$config['num_tag_open'] = '<li class="page-item">';
$config['num_tag_close'] = '</li>';
$config['next_link'] = '<i class="icon-feather-chevron-right"></i>';
$config['prev_link'] = '<i class="icon-feather-chevron-left"></i>';
$config['first_link'] = '<i class="icon-feather-chevron-left"></i> First';
$config['last_link'] = 'Last <i class="icon-feather-chevron-right"></i>';
$config['attributes'] = array('class' => 'page-link');