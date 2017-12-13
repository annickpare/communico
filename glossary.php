<?php
/*
Template Name: Glossary
*/
?>

<?php get_header(); ?>


<!-- Burger icon for top menu (< medium screen) -->
<div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
    <button class="menu-icon" type="button" data-toggle></button>
    <div class="title-bar-title">Menu</div>
</div>

<!-- Top bar -->
<div class="top-bar">

    <!-- Top menu -->
    <div class="top-bar-right">
        <nav>
            <ul id="example-menu" class=" medium-horizontal vertical dropdown menu" data-responsive-menu="accordion medium-dropdown">
                <!-- This menu renders ALL the pages included in the page tree. -->
                <?php sofad\theme\mini\get_menu('top', 0, [
                    'start_lvl' => '<ul class="menu vertical">',
                    'static_wrapper' => '<a><span>%2$s</span></a>',
                ]); ?>
            </ul>
        </nav>
    </div>


    <!-- Breadcrumb -->
    <div class="top-bar-left">
        <?php bloginfo('name'); ?> » <?php sofad\theme\mini\get_breadcrumb(); ?>
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

                    <h2>0 - F</h2>
                    <dl class="glossay-list">
                        <?php \sofad\theme\mini\get_glossary_range('0', 'g'); ?>
                    </dl>

                    <h2>G - Z</h2>
                    <dl class="glossay-list">
                        <?php \sofad\theme\mini\get_glossary_range('g'); ?>
                    </dl>
                </article>


            <?php endwhile; ?>

            <!-- Helper for course development. Conditional makes sure this is not rendered on export. -->
            <?php if(!\sofad\theme\mini\is_launching()): ?>
                <div class="sofadauteur-export-remove raw-link">
                    Lien de travail : <a href="<?php \sofad\theme\mini\get_id_url(); ?>"><?php \sofad\theme\mini\get_id_url(); ?></a>
                </div>
            <?php endif; ?>
        </main>


    </div>
</div>

<?php get_footer(); ?>
