<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">


    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

  <?php $current_user = wp_get_current_user(); ?>

  <div id="wrapper">

    <!-- Top bar -->
    <div class="top-bar">
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
    </div>
