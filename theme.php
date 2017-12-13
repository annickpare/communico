<?php
/*
Template Name: Thème
*/
?>

<?php get_header(); ?>

  <div class="row expanded">
      <div class="large-12 large-centered medium-12 columns">

          <main>
              <?php
              while ( have_posts() ) : the_post(); ?>

                  <!-- Content -->
                  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                      <h1><?php the_title(); ?></h1>

                      <div class="entry-content">
                          <ul>
                            <!-- This menu renders ALL the pages included in the page tree. -->
                            <?php sofad\theme\mini\get_menu('children', 1, [
                                'static_wrapper' => '<a><span>%2$s</span></a>',
                            ],''); ?>
                          </ul>
                          <?php the_content(); ?>
                      </div>
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
  <div class="push"></div>
</div>

<?php get_footer(); ?>
