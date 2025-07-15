<?php
// twentytwentyfive-wordpress-fse/functions.php

/**
 * Основные функции для блочной темы Twenty Twenty Five Wordpress FSE.
 */

// 1. Подключение стилей темы
function twentytwentyfive_wordpress_fse_styles() {
    // Подключаем основной стиль родительской темы Twenty Twenty-Five
    wp_enqueue_style( 'twentytwentyfive-style', get_template_directory_uri() . '/style.css' );

    wp_enqueue_style(
        'twentytwentyfive-wordpress-fse-style',
        get_stylesheet_uri(),
        array( 'twentytwentyfive-style' ), // Указываем зависимость от родительского стиля
        wp_get_theme()->get('Version') // Автоматическая версия для предотвращения кэширования
    );
}
add_action( 'wp_enqueue_scripts', 'twentytwentyfive_wordpress_fse_styles' );

// 2. Добавление поддержки различных функций темы
function twentytwentyfive_wordpress_fse_setup() {
    add_theme_support( 'wp-block-styles' ); // Поддержка стилей блоков
    add_theme_support( 'editor-styles' );   // Поддержка стилей редактора
    add_theme_support( 'responsive-embeds' ); // Адаптивные встраивания
    add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form', 'gallery', 'caption', 'style', 'script' ) ); // Поддержка HTML5
    add_theme_support( 'automatic-feed-links' ); // Поддержка RSS-лент

    // Для FSE темы очень важно указать, что она поддерживает Editor Style
    add_editor_style( 'style.css' );

    // Включаем поддержку миниатюр (featured images)
    add_theme_support( 'post-thumbnails' );
}

add_action( 'after_setup_theme', 'twentytwentyfive_wordpress_fse_setup' );


// 3. Регистрация пользовательской переменной запроса для пагинации (как мы делали ранее)
function twentytwentyfive_wordpress_fse_custom_query_vars( $vars ) {
    $vars[] = 'answers_page';
    return $vars;
}
add_filter( 'query_vars', 'twentytwentyfive_wordpress_fse_custom_query_vars' );

/**
 * Регистрация пользовательских блоков Gutenberg.
 */
function twentytwentyfive_wordpress_fse_register_blocks() {
    if ( function_exists( 'acf_register_block_type' ) ) {
        // Зарегистрировать блок "Связанные Neiro Ответы"
        acf_register_block_type( array(
            'name'            => 'related-neiro-answers',
            'title'           => __( 'Связанные Neiro Ответы', 'twentytwentyfive-wordpress-fse' ),
            'description'     => __( 'Выводит связанные записи типа Neiro Answer для текущего Neiro Chat.', 'wp-developer-notes-theme' ),
            'render_template' => 'blocks/related-neiro-answers/related-neiro-answers.php',
            'category'        => 'widgets', // Или 'common', 'layout'
            'icon'            => 'buddicons-replies',
            'keywords'        => array( 'neiro', 'answers', 'related', 'acf', 'chat' ),
            'supports'        => array(
                'align' => false,
                'html'  => false,
            ),
            'mode'            => 'auto', // Позволяет выбирать между "edit" и "preview"
        ));
    }
}
add_action( 'acf/init', 'twentytwentyfive_wordpress_fse_register_blocks' );