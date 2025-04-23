<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );
         
if ( !function_exists( 'child_theme_configurator_css' ) ):
    function child_theme_configurator_css() {
        wp_enqueue_style( 'chld_thm_cfg_child', trailingslashit( get_stylesheet_directory_uri() ) . 'style.css', array( 'twentytwentyfive-style','twentytwentyfive-style' ) );
    }
endif;
add_action( 'wp_enqueue_scripts', 'child_theme_configurator_css', 10 );

// END ENQUEUE PARENT ACTION


/*
 * Register Custom Post Type: Projects
 */
function custom_post_type() {

    $labels = array(
        'name'                  => _x( 'Projects', 'Post Type General Name', 'twentytwentyone' ),
        'singular_name'         => _x( 'Project', 'Post Type Singular Name', 'twentytwentyone' ),
        'menu_name'             => __( 'Projects', 'twentytwentyone' ),
        'parent_item_colon'     => __( 'Parent Project', 'twentytwentyone' ),
        'all_items'             => __( 'All Projects', 'twentytwentyone' ),
        'view_item'             => __( 'View Project', 'twentytwentyone' ),
        'add_new_item'          => __( 'Add New Project', 'twentytwentyone' ),
        'add_new'               => __( 'Add New', 'twentytwentyone' ),
        'edit_item'             => __( 'Edit Project', 'twentytwentyone' ),
        'update_item'           => __( 'Update Project', 'twentytwentyone' ),
        'search_items'          => __( 'Search Project', 'twentytwentyone' ),
        'not_found'             => __( 'Not Found', 'twentytwentyone' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'twentytwentyone' ),
    );

    $args = array(
        'label'                 => __( 'projects', 'twentytwentyone' ),
        'description'           => __( 'Project portfolio and case studies', 'twentytwentyone' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields' ),
        'taxonomies'            => array( 'project_type' ), // Linked taxonomy
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'show_in_nav_menus'     => true,
        'show_in_admin_bar'     => true,
        'menu_position'         => 5,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    );

    register_post_type( 'projects', $args );
}
add_action( 'init', 'custom_post_type', 0 );

/*
 * Register Custom Taxonomy: Project Type
 */
function create_project_type_taxonomy() {

    $labels = array(
        'name'              => _x( 'Project Types', 'taxonomy general name', 'twentytwentyone' ),
        'singular_name'     => _x( 'Project Type', 'taxonomy singular name', 'twentytwentyone' ),
        'search_items'      => __( 'Search Project Types', 'twentytwentyone' ),
        'all_items'         => __( 'All Project Types', 'twentytwentyone' ),
        'parent_item'       => __( 'Parent Project Type', 'twentytwentyone' ),
        'parent_item_colon' => __( 'Parent Project Type:', 'twentytwentyone' ),
        'edit_item'         => __( 'Edit Project Type', 'twentytwentyone' ),
        'update_item'       => __( 'Update Project Type', 'twentytwentyone' ),
        'add_new_item'      => __( 'Add New Project Type', 'twentytwentyone' ),
        'new_item_name'     => __( 'New Project Type Name', 'twentytwentyone' ),
        'menu_name'         => __( 'Project Types', 'twentytwentyone' ),
    );

    $args = array(
        'hierarchical'      => true, // Set to false for non-hierarchical (like tags)
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'project-type' ),
        'show_in_rest'      => true,
    );

    register_taxonomy( 'project_type', array( 'projects' ), $args );
}
add_action( 'init', 'create_project_type_taxonomy', 0 );

add_action('wp_ajax_get_architecture_projects', 'get_architecture_projects_ajax');
add_action('wp_ajax_nopriv_get_architecture_projects', 'get_architecture_projects_ajax');

function get_architecture_projects_ajax() {
    $is_logged_in = is_user_logged_in();
    $posts_per_page = $is_logged_in ? 6 : 3;

    $args = array(
        'post_type'      => 'projects',
        'posts_per_page' => $posts_per_page,
        'post_status'    => 'publish',
        'tax_query'      => array(
            array(
                'taxonomy' => 'project_type',
                'field'    => 'slug',
                'terms'    => 'architecture',
            ),
        ),
    );

    $query = new WP_Query($args);
    $data = [];

    if ($query->have_posts()) {
        foreach ($query->posts as $post) {
            $data[] = array(
                'id'    => $post->ID,
                'title' => get_the_title($post->ID),
                'link'  => get_permalink($post->ID),
            );
        }

        wp_send_json(array(
            'success' => true,
            'data'    => $data,
        ));
    } else {
        wp_send_json(array(
            'success' => true,
            'data'    => [],
        ));
    }

    wp_die(); // always terminate after Ajax handlers
}

function redirect_users_by_ip_prefix() {
    // Get the visitor's IP address
    $ip_address = $_SERVER['REMOTE_ADDR'];

    // Check if it starts with '77.29'
    if (strpos($ip_address, '77.29') === 0) {
        wp_redirect('https://www.google.com'); // Redirect target (change as needed)
        exit;
    }
}
add_action('template_redirect', 'redirect_users_by_ip_prefix');


function hs_give_me_coffee() {
    $response = wp_remote_get( 'https://coffee.alexflipnote.dev/random.json' );

    if ( is_wp_error( $response ) ) {
        return 'https://via.placeholder.com/400x300?text=No+coffee+for+you'; // fallback
    }

    $body = wp_remote_retrieve_body( $response );
    $data = json_decode( $body, true );

    if ( isset( $data['file'] ) ) {
        return esc_url_raw( $data['file'] );
    } else {
        return 'https://via.placeholder.com/400x300?text=Coffee+not+found';
    }
}


function hs_coffee_shortcode() {
    $coffee_link = hs_give_me_coffee();
    return '<p>Here is your coffee: <a href="' . esc_url( $coffee_link ) . '" target="_blank">' . esc_html( $coffee_link ) . '</a></p>';
}
add_shortcode( 'give_me_coffee', 'hs_coffee_shortcode' );


function hs_get_kanye_quotes( $count = 5 ) {
    $quotes = [];

    for ( $i = 0; $i < $count; $i++ ) {
        $response = wp_remote_get( 'https://api.kanye.rest/' );

        if ( is_wp_error( $response ) ) {
            $quotes[] = 'Could not fetch quote.';
            continue;
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( isset( $data['quote'] ) ) {
            $quotes[] = esc_html( $data['quote'] );
        } else {
            $quotes[] = 'No quote returned.';
        }
    }

    return $quotes;
}

function hs_kanye_quotes_shortcode() {
    $quotes = hs_get_kanye_quotes();
    $output = '<div class="kanye-quotes"><h3>Kanye Says:</h3><ul>';

    foreach ( $quotes as $quote ) {
        $output .= '<li>"' . $quote . '"</li>';
    }

    $output .= '</ul></div>';
    return $output;
}
add_shortcode( 'kanye_quotes', 'hs_kanye_quotes_shortcode' );


