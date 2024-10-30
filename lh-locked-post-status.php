<?php
/*
Plugin Name: LH Locked Post Status
Version: 1.02
Plugin URI: https://lhero.org/portfolio/lh-locked-post-status/
Description: Creates two additional post statuses of Publicly Locked and Privately Locked
Author: Peter Shaw
Author URI: https://shawfactor.com
*/



if (!class_exists('LH_Custom_post_status_class')) {

require_once('includes/lh-custom-post-status-class.php');

}


$lh_locked_public_post_status = new LH_Custom_post_status_class('public_lock','publicly locked','Publicly Locked <span class="count">(%s)</span>','read','manage_options');

$lh_locked_private_post_status = new LH_Custom_post_status_class('private_lock','privately locked','Privately locked <span class="count">(%s)</span>','read_private_posts','manage_options');









?>