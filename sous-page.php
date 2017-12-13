<?php
/*
Template Name: Sous-page
*/
?>

<?php get_header('base'); ?>

<?php $current_user = wp_get_current_user(); ?>

<div id="wrapper">

  <!-- Top bar -->
  <header class="top-bar">
    <div class="row expanded valign-bottom">

      <div id="logo" class="large-6 medium-5 small-12 columns">
        <h1>Communico</h1>
        <h2>Trousse de formation en communication orale</h2>
      </div>

      <div class="zone-utilitaire large-6 medium-7 small-12 columns">
        <ul>
        <li>Bonjour <?php echo $current_user->user_firstname; ?></li>
        <li><a href="#" class="goBack">Dernière page visitée</a></li>
        <li><a href="#">Se déconnecter</a></li>
      </div>

      <nav class="zone-menus">
        <ul>
          <li><a href="http://auteur.sofad.qc.ca/communico/page/page-principale.html">TDM</a></li>
          <li><a href="#">ABC</a></li>
          <li><a href="#">BAO</a></li>
        </ul>
      </nav>

    </div>
  </header>


  <?php $ancestors = get_ancestors( $post->ID, 'page' ); ?>

  <div class="content row expanded">
      <div class="titre-theme large-3 medium-3 small-12 columns no-padding">
        <h1><?php sofad\theme\mini\get_parent_title(4); ?></h1>
        <h2><?php sofad\theme\mini\get_parent_title(3); ?></h2>
        <div>
            <ul id="menu-themes">
              <?php sofad\theme\mini\get_menu('top-theme-ancestor', 1, [
                  'start_lvl' => '<ul>',
                  'static_wrapper' => '<a><span>%2$s</span></a>',
              ],''); ?>
            </ul>
        </div>
      </div>
      <div class="large-9 medium-9 small-12 columns no-padding">

          <main>
            <nav class="row expanded">
              <ul id="menu-theme" class="large-9 columns no-padding medium-horizontal vertical dropdown menu" data-responsive-menu="accordion medium-dropdown">
                <?php sofad\theme\mini\get_menu('top-theme-sp', 2, [
                    'start_lvl' => '<ul class="menu vertical">',
                    'static_wrapper' => '<a><span>%2$s</span></a>',
                ],''); ?>
              </ul>
              <form class="large-3 columns no-padding">
                <fieldset>
                  <select id="dynamic_select">
                    <?php sofad\theme\mini\get_menu('siblings', 0, [
                        'start_lvl' => '',
                        'end_lvl' => '',
                        'start_el' => '',
                        'end_el' => '',
                        'link_wrapper' => '<option value="%s" class="%4$s">%s</option>',
                        'static_wrapper' => '<span>%2$s</span>',
                    ],''); ?>
                  </select>
                </fieldset>
              </form>
            </nav>
            <?php
              if(has_term('amorce', 'sofadp_tags', $post)) {
                $class = 'amorce';
              }else if(has_term('bilan', 'sofadp_tags', $post)) {
                $class = 'bilan';
              }else if(has_term('contenu', 'sofadp_tags', $post)) {
                $class = 'contenu';
              }
            ?>
            <div class="icone icone-<?php echo $class; ?>"></div>
              <?php
              while ( have_posts() ) : the_post(); ?>

                  <!-- Content -->
                  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                      <h1><?php sofad\theme\mini\get_parent_title(1,'%2$s'); ?></h1>

                      <div class="entry-content">
                          <?php the_content(); ?>
                      </div>
                  </article>

                  <div class="centered">
                    <nav>
                        <ul class="pagination">

                            <?php  sofad\theme\mini\get_previous(1,
                                '<li class="pagination-previous disabled">Précédent</li>',
                                '<li class="pagination-previous"><a href="%s" title="%s">Précédent</a></li>'
                            ); ?>

                            <?php /*sofad\theme\mini\get_menu('siblings', 1, [
                                    'link_wrapper' => '<a href="%s" title="%2$s" class="%4$s">%3$s</a>'
                            ],'');*/
                              ?>

                            <?php sofad\theme\mini\get_next(1,
                                '<li class="pagination-next disabled">Suivant</li>',
                                '<li class="pagination-next"><a href="%s" title="%s">Suivant</a></li>'
                            ); ?>
                        </ul>
                    </nav>
                  </div>

                  <ul class="zone-page-suivante">
                    <?php  sofad\theme\mini\get_next_section(
                        '<li class="pagination-next disabled">&nbsp;</li>',
                        '<li class="pagination-next"><a href="%s">%2$s</a></li>'
                    ); ?>
                  </ul>

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
