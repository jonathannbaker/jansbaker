<?php
/*
Plugin Name: Display Product Variation Metadata
Description: Display WooCommerce Product Variation metadata in a formatted meta box.
Plugin URI: https://github.com/scottyzen/display_variation_metadata
Version:     1.0.1
Author:      Scott Kennedy
Author URI:  https://scottyzen.com
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
    $args = [ 'post_type' => 'product_variation', 'numberposts' => -1, 'post_parent' => get_the_ID() ];
    $variations = get_posts($args);
    ?>
    <style>table.meta{width:100%;border-spacing:0;border-radius:5px;overflow:hidden}table.meta thead{visibility:hidden;position:absolute;width:0;height:0}table.meta th{background:#27323E;color:#fff}table.meta td:nth-child(1){background:#27323E;color:#fff;border-radius:1em 1em 0 0}table.meta th,td{padding:1em}table.meta tr,td{display:block}table.meta td{position:relative;border-right: 1px solid #c7c7c7;}table.meta td::before{content:attr(data-label);position:absolute;left:0;padding-left:1em;font-weight:600;font-size:.9em;text-transform:uppercase}table.meta tr{margin-bottom:1.5em;vertical-align: baseline;border:1px solid #ddd;border-radius:5px;text-align:right}table.meta tr:last-of-type{margin-bottom:0}table.meta td:nth-child(n+2):nth-child(odd){background-color:#efefef}@media only screen and (min-width:768px){table.meta{max-width:1200px;margin:0 auto;border:1px solid #ddd}table.meta thead{visibility:visible;position:relative}table.meta th{text-align:left;text-transform:uppercase;font-size:.9em}table.meta tr{display:table-row;border:none;border-radius:0;text-align:left}table.meta tr:nth-child(even){background-color:#efefef}table.meta td{display:table-cell}table.meta td::before{content:none}table.meta td:nth-child(1){background:0 0;color:#444;border-radius:0}table.meta td:nth-child(n+2):nth-child(odd){background-color:transparent}}table pre {white-space: pre-wrap;}</style>
    <?php

    foreach ($variations as $variation) {
        $variation_id = $variation->ID;
        $variation_title = $variation->post_title;
        $meta_data = get_post_meta($variation_id);
    
        echo '<table class="meta">';
        echo '<h4>' . $variation_title . '</h4>';
        echo '<thead><tr><th>Key</th><th>Value</th></tr></thead>';
        echo '<tbody>';
        foreach ($meta_data as $key => $value) {
            $unsterialized_value = maybe_unserialize($value[0]);

            echo '<tr><td>' . $key . '</td><td>';
            highlight_string("<?php " . var_export_short($unsterialized_value, true) . " ?>");
            echo '</td></tr>';
        }
        echo '</tbody></table>';
    }
}
} else {
    return;
}

function var_export_short($data, $return=true){
    $dump = var_export($data, true);
    $dump = preg_replace('#(?:\A|\n)([ ]*)array \(#i', '[', $dump); // Starts
    $dump = preg_replace('#\n([ ]*)\),#', "\n$1],", $dump); // Ends
    $dump = preg_replace('#=> \[\n\s+\],\n#', "=> [],\n", $dump); // Empties

    if (gettype($data) == 'object') { // Deal with object states
        $dump = str_replace('__set_state(array(', '__set_state([', $dump);
        $dump = preg_replace('#\)\)$#', "])", $dump);
    } else { 
        $dump = preg_replace('#\)$#', "]", $dump);
    }

    if ($return===true) {
        return $dump;
    } else {
        echo $dump;
    }
}
