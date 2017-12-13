<?php
/*
Template Name: Section
*/
?>

<?php get_header(); ?>

<!-- Top bar -->
<div class="top-bar">

    <!-- Breadcrumb -->
    <div class="top-bar-left">
        Aperçu d'un test
    </div>
</div>

<div class="row">
    <div class="large-8 large-centered medium-10 medium-offset-2 columns">

        <main>
            <?php
            while ( have_posts() ) : the_post(); ?>

                <!-- Content -->
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <h1><?php echo the_title(); ?></h1>

                    <div class="entry-content">
                        <?php the_content(); ?>
                    </div>
                </article>

            <?php endwhile; ?>

            <?php if(!\sofad\theme\mini\is_launching()): ?>
                <div class="sofadauteur-export-remove raw-link">
                    Lien de travail : <a href="<?php \sofad\theme\mini\get_id_url(); ?>"><?php \sofad\theme\mini\get_id_url(); ?></a>
                </div>
            <?php endif; ?>
        </main>


    </div>
</div>

<?php get_footer(); ?>
