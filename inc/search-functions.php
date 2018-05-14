<?php

add_action('wp_footer', function () {

    echo fs_get_template('search/block');
});