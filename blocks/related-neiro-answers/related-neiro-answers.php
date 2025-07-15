<?php
// blocks/related-neiro-answers/related-neiro-answers.php

/**
 * Блок Gutenberg: Связанные Neiro Ответы.
 * Выводит связанные записи типа Neiro Answer для текущего Neiro Chat.
 */

// Получаем ID текущего neiro_chat.
// Важно: в динамическом блоке get_the_ID() будет возвращать ID поста, в котором блок размещен.
$current_neiro_chat_id = get_the_ID();

if ( ! $current_neiro_chat_id ) {
    // Если по какой-то причине ID поста не получен (например, блок на черновике без ID),
    // или если это не single-neiro_chat страница, можно вывести сообщение или выйти.
    if ( is_admin() ) { // В редакторе Гутенберг можно вывести заглушку
        echo '<p style="background:#f0f0f0; padding:1em; border:1px dashed #ccc;">Блок "Связанные Neiro Ответы": ID текущего Neiro Chat не найден. Убедитесь, что блок размещен на странице записи Neiro Chat.</p>';
    }
    return;
}

// Определяем текущую страницу для пагинации связанных ответов
// Используем нашу зарегистрированную переменную запроса 'answers_page'
$paged_answers = ( get_query_var( 'answers_page' ) ) ? absint( get_query_var( 'answers_page' ) ) : 1;

// Аргументы для WP_Query для получения связанных neiro_answer
$args = array(
    'post_type'      => 'neiro_answer',
    'posts_per_page' => 5, // Количество ответов на страницу
    'paged'          => $paged_answers,
    'meta_query'     => array(
        array(
            'key'     => 'neiro_chat', // Имя поля Relationship в CPT 'neiro_answer'
            'value'   => '"' . $current_neiro_chat_id . '"', // Важно: ID должен быть обернут в кавычки
            'compare' => 'LIKE', // Используем LIKE для поиска в сериализованных данных
        ),
    ),
    'orderby'        => 'date',
    'order'          => 'DESC',
);

$related_answers_query = new WP_Query( $args );

if ( $related_answers_query->have_posts() ) :
    ?>
    <div class="neiro-chat-related-answers-block">
        <ul class="related-answers-list">
            <?php while ( $related_answers_query->have_posts() ) : $related_answers_query->the_post(); ?>
                <li class="related-answer-item">
                    <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                    <div class="answer-content">
                        <?php the_content(); // Или другое поле ACF, например: get_field('answer_text_field') ?>
                    </div>
                </li>
            <?php endwhile; ?>
        </ul>

        <?php
        // Вывод пагинации для связанных ответов
        $pagination_args = array(
            'base'      => add_query_arg( 'answers_page', '%#%', get_permalink( $current_neiro_chat_id ) ), // Базовый URL для пагинации
            'format'    => '',
            'current'   => max( 1, $paged_answers ),
            'total'     => $related_answers_query->max_num_pages,
            'prev_text' => '&laquo; Предыдущие',
            'next_text' => 'Следующие &raquo;',
            'type'      => 'list', // Выводим как список (ul)
        );

        echo '<nav class="pagination related-answers-nav">';
        echo paginate_links( $pagination_args );
        echo '</nav>';
        ?>
    </div>
    <?php
else :
    echo '<p>Нет связанных ответов для этого чата.</p>';
endif;

// Обязательно сбрасываем данные поста после кастомного запроса.
wp_reset_postdata();
?>