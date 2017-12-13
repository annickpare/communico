<?php
/*
Template Name: Page principale - publique
*/
?>

<?php get_header('base'); ?>

<div id="wrapper">

  <!-- Burger icon for top menu (< medium screen) -->
  <div class="title-bar" data-responsive-toggle="example-menu" data-hide-for="medium">
      <button class="menu-icon" type="button" data-toggle></button>
      <div class="title-bar-title">Menu</div>
  </div>

  <!-- Top bar -->
  <div class="top-bar">
    <div class="row expanded">

      <div id="logo" class="large-6 medium-6 small-12 columns">
        <h1>Communico</h1>
        <h2>Trousse de formation en communication orale</h2>
      </div>

      <div class="bloc-inscription large-3 medium-3 small-12 columns">
        <button href="#">Inscrivez-vous</button>
        <p>pour conserver vos documents et pouvoir revoir vos activités de formation.</p>
        <p><a href="#">Pseudonyme ou mot de passe oublié?</a></p>
      </div>

      <div class="bloc-connexion large-3 medium-3 small-12 columns">
        <form>
          <fieldset>
            <label>
              Pseudonyme
              <input type="text" id="login-pseudo" />
            </label>
            <label>
              Mot de passe
              <input type="password" id="login-password" />
            </label>
          </fieldset>
        </form>
      </div>

    </div>
  </div>

  <div class="row expanded">
      <div class="large-12 large-centered medium-12 columns">

          <main>
              <?php
              while ( have_posts() ) : the_post(); ?>

                  <!-- Content -->
                  <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                      <div class="entry-content">
                          <?php the_content(); ?>
                          <ul>
                            <?php
                              $defaults = array(
                                  'child_of' => 3,
                                  'title_li' => '',
                                  'exclude'  => '8,12,14,16,18,20,26,28,30',
                              );
                              wp_list_pages($defaults);
                            ?>
                          </ul>
                          <ul>
                            <?php
                              $defaults = array(
                                  'child_of' => 3,
                                  'title_li' => '',
                                  'exclude'  => '6,20,26,28,30',
                              );
                              wp_list_pages($defaults);
                            ?>
                          </ul>
                          <ul>
                            <?php
                              $defaults = array(
                                  'child_of' => 3,
                                  'title_li' => '',
                                  'exclude'  => '6,8,12,14,16,18',
                              );
                              wp_list_pages($defaults);
                            ?>
                          </ul>
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
