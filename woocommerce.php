<?php

/**
 * Deletes all users that don't have WooCommerce order assigned to their email
 */
function clearUsers(int $assignTo) {
    $users = get_users(array('role__in' => array('customer')));

    require_once(ABSPATH.'wp-admin/includes/user.php');

    $args = array(
        'limit' => -1,
    );

    $query = new WC_Order_Query($args);
    $orders = $query->get_orders();

    $emails_to_skip = [];

    foreach ($orders as $order) {
        if ($order->billing_email) {
            $emails_to_skip[$order->billing_email] = true;
        }
    }

    foreach ($users as $user) {
        if (!array_key_exists($user->user_email, $emails_to_skip)) {
            wp_delete_user($user->ID, $assignTo);
            echo 'Deleted: '.$user->id.'<br>';
        }
    }
}