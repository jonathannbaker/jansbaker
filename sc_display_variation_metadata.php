<?php
/*
Plugin Name: Display Product Variation Metadata
Description: Display product variation metadata in a formated meta box.
Version:     1.0
Author:      Scott Kennedy
Author URI:  https://scottyzen.com]
*/

// check if woocommerce is active and if not, return
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
    // Add add_meta_boxes to the product post type
add_action( 'add_meta_boxes', 'add_custom_meta_boxes' );
function add_custom_meta_boxes() {
    add_meta_box(
        'product_variation_meta',
        __( 'Product Variation Metadata', 'textdomain' ),
        'product_variation_meta_callback',
        'product',
        'normal',
        'default'
    );
}
// display all product variation meta data on product page
function product_variation_meta_callback (){    
    $args = [
        'post_type'     => 'product_variation',
        'numberposts'   => -1,
        'post_parent'   => get_the_ID()
    ];
    $variations = get_posts($args);
    ?>
    <style>
    table.meta{width:100%;border-spacing:0;border-radius:5px;overflow:hidden}table.meta thead{visibility:hidden;position:absolute;width:0;height:0}table.meta th{background:#27323E;color:#fff}table.meta td:nth-child(1){background:#27323E;color:#fff;border-radius:1em 1em 0 0}table.meta th,td{padding:1em}table.meta tr,td{display:block}table.meta td{position:relative;border-right: 1px solid #c7c7c7;}table.meta td::before{content:attr(data-label);position:absolute;left:0;padding-left:1em;font-weight:600;font-size:.9em;text-transform:uppercase}table.meta tr{margin-bottom:1.5em;vertical-align: baseline;border:1px solid #ddd;border-radius:5px;text-align:right}table.meta tr:last-of-type{margin-bottom:0}table.meta td:nth-child(n+2):nth-child(odd){background-color:#efefef}@media only screen and (min-width:768px){table.meta{max-width:1200px;margin:0 auto;border:1px solid #ddd}table.meta thead{visibility:visible;position:relative}table.meta th{text-align:left;text-transform:uppercase;font-size:.9em}table.meta tr{display:table-row;border:none;border-radius:0;text-align:left}table.meta tr:nth-child(even){background-color:#efefef}table.meta td{display:table-cell}table.meta td::before{content:none}table.meta td:nth-child(1){background:0 0;color:#444;border-radius:0}table.meta td:nth-child(n+2):nth-child(odd){background-color:transparent}}
    </style>
    <?php

    foreach ($variations as $variation) {
        $variation_id = $variation->ID;
        $variation_title = $variation->post_title;
        $meta_data = get_post_meta($variation_id);
    
        echo '<table class="meta">';
        echo '<h3>' . $variation_title . '</h3>';
        echo '<thead><tr><th>Key</th><th>Value</th></tr></thead>';
        echo '<tbody>';
        foreach ($meta_data as $key => $value) {
            $unsterialized_value = maybe_unserialize($value[0]);
            echo '<tr><td>' . $key . '</td><td>';
            echo '<pre>';
            var_export($unsterialized_value);
            echo '</pre>';
            echo '</td></tr>';
        }
        echo '</tbody></table>';

    }
}

    
    } else {
        return;
    }
?>
