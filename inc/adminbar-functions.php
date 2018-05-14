<?php

add_action('admin_bar_menu', function ($wpAdminBar) {

    $wpAdminBar->add_node([
        'id'    => 'fs_admin_index',
        'title' => 'FastSearch',
        'href'  => '#',
        'meta'  => [],
    ]);

    $wpAdminBar->add_node([
        'id'     => 'fs_admin_sync',
        'parent' => 'fs_admin_index',
        'title'  => fs_get_template('admin/topbar/sync', [
            'site_url' => get_site_url(),
        ]),
        'meta'   => [
            'class' => 'fs_admin_sync',
        ],
    ]);

}, 999);
