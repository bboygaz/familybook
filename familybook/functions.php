<?php

// Theme support :
add_theme_support( 'post-thumbnails' );
add_theme_support( 'title-tag' );
add_theme_support( 'automatic-feed-links' );

// Ajoute le CSS et le JavaScript
function sgstarter_enqueue_script() {
    //css
    wp_enqueue_style('ion-icons', 'http://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css' );
    wp_enqueue_style('theme', get_stylesheet_directory_uri() . '/css/theme.css' );

    // JS
    $js_directory = get_template_directory_uri() . '/js/';
    wp_enqueue_script('jquery');
    wp_enqueue_script('front-js', get_stylesheet_directory_uri() . '/js/front.js', '', '', true );

    wp_localize_script( 'front-js', 'POST_SUBMITTER', array(
        'root' => esc_url_raw( rest_url() ),
        'nonce' => wp_create_nonce( 'wp_rest' ),
        'success' => __( 'Thanks for your submission!', 'your-text-domain' ),
        'failure' => __( 'Your submission could not be processed.', 'your-text-domain' ),
        'current_user_id' => get_current_user_id()
    ));

}
add_action( 'wp_enqueue_scripts', 'sgstarter_enqueue_script' );

/* DISABLE ADMIN BAR */
show_admin_bar(false);

/* REST API CUSTOM HEADPOINTS */
function family_get_posts() {

    $mois = ['décembre', 'janvier', 'février', 'mars', 'avril', 'mai', 'juin', 'juillet', 'août', 'septembre', 'octobre', 'novembre', 'décembre'];
    $jours = ['dimanche', 'lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche'];

    $post_data = array();

    $args =array(
        'post_type' => 'post'
    );
    $post_list = get_posts($args);
    
    foreach($post_list as $post) {
        
        $author = get_userdata($post->post_author)->data->display_name;

        $date = strtotime($post->post_date);
        $date = date('w-j-n-Y-G:i', $date);
        $date = explode('-', $date);
        $date = $jours[$date[0]] . ' ' . $date[1] . ' ' . $mois[$date[2]] . ' ' . $date[3] . ', à ' . $date[4] ;

        $thumbnail = get_the_post_thumbnail_url($post->ID);

        $comments_data = [];
        $comments_list = get_comments(array(
            'post_id' => $post->ID,
            'orderby' => 'comment_date',
            'order' => 'DESC'
        ));
        foreach($comments_list as $comment) {

            $comment_date = strtotime($comment->comment_date);
            $comment_date = date('w-j-n-Y-G:i', $comment_date);
            $comment_date = explode('-', $comment_date);
            $comment_date = $jours[$comment_date[0]] . ' ' . $comment_date[1] . ' ' . $mois[$comment_date[2]] . ' ' . $comment_date[3] . ', à ' . $comment_date[4] ;

            array_push($comments_data, array(
                'author' => $comment->comment_author,
                'date' => $comment_date,
                'content' => $comment->comment_content
            ));
        }
        
        array_push($post_data, array(
            'ID' => $post->ID,
            'author' => $author,
            'date' => $date,
            'content' => $post->post_content,
            'comments_count' => $post->comment_count,
            'comments' => $comments_data,
            'thumbnail' => $thumbnail
        ));

    }

    return $post_data;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'familybook/v1', '/posts/', array(
            'methods' => 'GET',
            'callback' => 'family_get_posts'
    ) );
} );

// Delete this
function family_test(){
    $args =array(
        'post_type' => 'post'
    );
    $post_list = get_posts($args);

    return $post_list;
}

add_action( 'rest_api_init', function () {
    register_rest_route( 'familybook/v1', '/test/', array(
            'methods' => 'GET',
            'callback' => 'family_test'
    ) );
} );
