<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title><?php wp_title(); ?></title>
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

    <noscript>
        You need to enable JavaScript to run this app.
    </noscript>
    <div id="root"></div>
