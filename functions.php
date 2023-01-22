<?php
/*
Plugin Name: LevelUP Pricing Table
*/

// Enqueue stylesheets

function levelup_pricing_table_enqueue_styles()
{
    wp_enqueue_style('levelup-pricing-table-styles', plugin_dir_url(__FILE__) . 'css/levelup-pricing-table.css');
}
add_action('admin_enqueue_scripts', 'levelup_pricing_table_enqueue_styles');

function levelup_pricing_enqueue_scripts()
{
    wp_enqueue_script('levelup_pricing_script', plugin_dir_url(__FILE__) . 'js/levelup-pricing.js', array(), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'levelup_pricing_enqueue_scripts');

// Register custom post type
function levelup_pricing_table_post_type()
{
    $labels = array(
        'name'               => 'Pricing Tables',
        'singular_name'      => 'Pricing Table',
        'menu_name'          => 'LevelUP Pricing Table',
        'name_admin_bar'     => 'Pricing Table',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Pricing Table',
        'new_item'           => 'New Pricing Table',
        'edit_item'          => 'Edit Pricing Table',
        'view_item'          => 'View Pricing Table',
        'all_items'          => 'All Pricing Tables',
        'search_items'       => 'Search Pricing Tables',
        'parent_item_colon'  => 'Parent Pricing Tables:',
        'not_found'          => 'No pricing tables found.',
        'not_found_in_trash' => 'No pricing tables found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'levelup-pricing'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array('title')
    );

    register_post_type('levelup_pricing', $args);
}
add_action('init', 'levelup_pricing_table_post_type');

// Add meta boxes for custom fields
function levelup_pricing_table_meta_boxes()
{
    add_meta_box('levelup_pricing_table_meta_box', 'Pricing Table Details', 'levelup_pricing_table_meta_box_callback', 'levelup_pricing', 'normal', 'high');
}
add_action('add_meta_boxes', 'levelup_pricing_table_meta_boxes');

// Add column meta box

function levelup_pricing_table_meta_box_callback($post)
{

    wp_nonce_field(basename(__FILE__), 'levelup_pricing_table_nonce');

    $price = get_post_meta($post->ID, 'price', true);
    $currency = get_post_meta($post->ID, 'currency', true);
    $title = get_post_meta($post->ID, 'title', true);
    $features = get_post_meta($post->ID, 'features', true);

    // Get the button text and url from post meta
    $button_text = get_post_meta($post->ID, 'levelup_pricing_table_button_text', true);
    $button_url = get_post_meta($post->ID, 'levelup_pricing_table_button_url', true);
    $new_tab = get_post_meta($post->ID, 'levelup_pricing_table_button_new_tab', true);

?>
<div id="levelup-pricing-table-columns">
    <div class="levelup-pricing-table-column">
        <label for="levelup_pricing_table_price">Price:</label>
        <input type="number" id="levelup_pricing_table_price" name="levelup_pricing_table_price" value="<?php echo esc_attr($price); ?>" size="25" />
        <label for="levelup_pricing_table_currency">Currency:</label>
        <select id="levelup_pricing_table_currency" name="levelup_pricing_table_currency">
    <option value="" disabled <?php echo !$currency ? 'selected' : ''?>>Select Currency</option>
    <option value="USD" <?php selected($currency, 'USD', true); ?>>USD</option>
    <option value="EUR" <?php selected($currency, 'EUR', true); ?>>EUR</option>
    <option value="GBP" <?php selected($currency, 'GBP', true); ?>>GBP</option>
</select>
        <label for="levelup_pricing_table_title">Column Title:</label>
        <input type="text" id="levelup_pricing_table_title" name="levelup_pricing_table_title" value="<?php echo esc_attr($title); ?>" size="25" />

        <label>Features:</label>
        <div class="levelup-pricing-table-features">
            <?php if (!empty($features)) : ?>
                <?php foreach ($features as $feature) : ?>
                    <div>
    <input type="text" name="levelup_pricing_table_features[]" value="<?php echo esc_attr($feature); ?>" size="25" />
    <button type="button" class="remove">Remove</button>
</div>
                <?php endforeach; ?>
            <?php else : ?>
                <div>
                    <input type="text" name="levelup_pricing_table_features[]" size="25" />
                </div>
            <?php endif; ?>
            
        </div>
        <button type="button" class="add">Add Feature</button>

        <label for="levelup_pricing_table_button_text">Button Text</label>
        <input type="text" id="levelup_pricing_table_button_text" name="levelup_pricing_table_button_text" value="<?php echo esc_attr($button_text); ?>" size="25" />
        <label for="levelup_pricing_table_button_url">Button URL</label>
        <input type="text" id="levelup_pricing_table_button_url" name="levelup_pricing_table_button_url" value="<?php echo esc_attr($button_url); ?>" size="25" />
        <label for="levelup_pricing_table_button_new_tab">Open in New Tab</label>
        <input type="checkbox" id="levelup_pricing_table_button_new_tab" name="levelup_pricing_table_button_new_tab" value="1" <?php if($new_tab) {echo 'checked';} ?> />


    </div>

    </div>

<?php

}

// Add column metabox

function levelup_pricing_table_add_column_meta_box() {
    add_meta_box( 'levelup_pricing_table_add_column_meta_box', 'Add Column', 'levelup_pricing_table_add_column_callback', 'levelup_pricing', 'side', 'core' );
}
add_action( 'add_meta_boxes', 'levelup_pricing_table_add_column_meta_box' );

function levelup_pricing_table_add_column_callback( $post ) {
    // Add the button HTML here
    echo '<button id="add-column-button">New Column +</button>';
}

// Save the pricing table

/*remove_action( 'save_post', 'levelup_pricing_table_save_meta_box' );*/

function levelup_pricing_table_save_meta_box_data($post_id) {
    if (!isset($_POST['levelup_pricing_table_nonce']) || !wp_verify_nonce($_POST['levelup_pricing_table_nonce'], basename(__FILE__)) || defined('DOING_AUTOSAVE') || !current_user_can('edit_post', $post_id)) {
        return;
    }

    update_post_meta($post_id, 'title', sanitize_text_field($_POST['levelup_pricing_table_title']));
    update_post_meta($post_id, 'price', sanitize_text_field($_POST['levelup_pricing_table_price']));
    update_post_meta($post_id, 'currency', sanitize_text_field($_POST['levelup_pricing_table_currency']));
    update_post_meta($post_id, 'features', isset($_POST['levelup_pricing_table_features']) ? array_map('sanitize_text_field', $_POST['levelup_pricing_table_features']) : array());
    update_post_meta($post_id, 'levelup_pricing_table_button_text', sanitize_text_field($_POST['levelup_pricing_table_button_text']));
    update_post_meta($post_id, 'levelup_pricing_table_button_url', esc_url_raw($_POST['levelup_pricing_table_button_url']));
    update_post_meta($post_id, 'levelup_pricing_table_button_new_tab', isset($_POST['levelup_pricing_table_button_new_tab']) ? sanitize_text_field($_POST['levelup_pricing_table_button_new_tab']) : '');
}

add_action('save_post', 'levelup_pricing_table_save_meta_box_data');
