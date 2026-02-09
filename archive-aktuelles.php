<?php

/**
 * Archive template for Custom Post Type "Aktuelles"
 *
 * This template is used to display the archive page for "Aktuelles" posts.
 * URL: /aktuelles/
 */

get_header(); ?>

<!-- Hero Section -->
<section class="module hero has-gradient no-buttons">
    <div class="background-image" aria-hidden="true">
        <img src="/wp-content/uploads/2026/02/2024_10_23_StMELF_FORSTSCHULE_TECHNIKER_0854-scaled.jpg"
            srcset="/wp-content/uploads/2026/02/2024_10_23_StMELF_FORSTSCHULE_TECHNIKER_0854-scaled.jpg" sizes="100vw" alt="" loading="lazy"
            decoding="async" />
    </div>

    <div class="container">
        <div class="text-wrapper">
            <h1 class="headline headline-1">
                Aktuelles
            </h1>
          
        </div>
    </div>
</section>
<section class="module block-text einleitung-aktuelles">


    <div class="container">
        <div class="text-wrapper">
         <h2 class="headline headline-2">Mitteilungen & Termine</h2>
          
        </div>
    </div>
</section>
            


<div class="aktuelles-archive">
    <div class="container">

        <?php if (have_posts()) : ?>
            <div class="aktuelles-list">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="aktuelles-item" id="post-<?php the_ID(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="aktuelles-thumbnail">
                                <a href="<?php the_permalink(); ?>" aria-label="<?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail('medium', array(
                                        'loading' => 'lazy',
                                        'decoding' => 'async'
                                    )); ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <div class="aktuelles-content">
                            <header class="entry-header">
                                <h2 class="entry-title headline-2 headline">
                                        <?php the_title(); ?>
                                </h2>
                                <div class="entry-meta">
                                  <span>Datum der Mitteilung:</span>
                                    <time class="entry-date" datetime="<?php echo get_the_date('c'); ?>">
                                        <?php echo get_the_date('j. F Y'); ?>
                                    </time>
                                </div>
                            </header>

                            <div class="entry-content">
                                <?php the_content(); ?>
                            </div>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php 
            // Pagination
            the_posts_pagination(array(
                'mid_size' => 2,
                'prev_text' => __('Vorherige', 'textdomain'),
                'next_text' => __('Nächste', 'textdomain'),
                'before_page_number' => '<span class="screen-reader-text">Seite </span>',
                'class' => 'aktuelles-pagination'
            )); 
            ?>

        <?php else : ?>
            <div class="no-posts">
                <h2 class="headline-2">Keine Beiträge gefunden</h2>
                <p>Es sind noch keine aktuellen Beiträge vorhanden.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>