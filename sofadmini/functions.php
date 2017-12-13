<?php

namespace sofad\theme\mini;

include __DIR__ . '/classes/MenuWalker.php';

/**
 * Echoes breadcrumb according to parental hierarchy. May only be used in The Loop.
 *
 * A link will be placed on each page of the breadcrumb, except if the page is in category 'groupeur' of the
 * 'sofadp_tags' taxonomy.
 *
 * @param string $html_elt_wrapper printf-formatted HTML wrapper for each breadcrumb element
 * @param string $html_separator
 */
function get_breadcrumb($html_elt_wrapper = '%s', $html_separator = ' » ') {

    $post = get_post();
    if(!$post) {
        return;
    }

    $posts_in_breadcrumb = [$post];

    while(0 != $post->post_parent) {
        $posts_in_breadcrumb[] = $post = get_post($post->post_parent);
    }

    $posts_in_breadcrumb = array_reverse($posts_in_breadcrumb);

    $post_titles = array_map(function(\WP_Post $post) use ($html_elt_wrapper) {

        $wrapper = has_term('groupeur', 'sofadp_tags', $post) ?
            $html_elt_wrapper :
            sprintf($html_elt_wrapper, "<a href='" . get_permalink($post) . "'>%s</a>") ;

        return sprintf($wrapper, $post->post_title);
    }, $posts_in_breadcrumb);

    echo implode($html_separator, $post_titles);
}



/**
 * Echoes the hyperlinked title of a parent page. If the page is in the 'groupeur' category, the first non 'groupeur'
 * child page will be used to produce the hyperlink.
 *
 * @param int $ancestor_lvl 0 = self, 1 = immediate parent, -1 = top of parent hierarchy
 * @param string $wrapper
 */
function get_parent_title($ancestor_lvl = 1, $wrapper="<a href='%s'>%s</a>") {
    $post = get_post();
    if (!$post) {
        return;
    }

    // Find list of pages this button navigates through

    if (0 == $ancestor_lvl) {
        echo sprintf($wrapper, get_permalink($post), $post->post_title);
        return;
    }

    $ancestor = $post;
    $ancestry = [ $ancestor ];
    while(0 != $ancestor->post_parent) {
        $ancestry[] = $ancestor = get_post($ancestor->post_parent);
    }

    $post_to_render = $ancestor_lvl < 0 ?
        $ancestry[count($ancestry) + $ancestor_lvl] :
        $ancestry[$ancestor_lvl] ;

    if(has_term('groupeur', 'sofadp_tags', $post_to_render)) {
        $children = get_pages([
            'child_of' => $post_to_render->ID,
            'sort_column' => 'menu_order'
        ]);

        if(0 == count($children)) {
            $render_url = '';
        } else {
            get_page_hierarchy($children);

            // Filter out dummy pages

            $children = array_filter($children, function ($post) {
                return !has_term('groupeur', 'sofadp_tags', $post);
            });

            $render_url = get_permalink(reset($children));
        }

    } else {
        $render_url = get_permalink($post_to_render);
    }

    echo sprintf($wrapper, $render_url, $post_to_render->post_title);
}



/**
 * Echoes a hierarchic menu based on parent relationships. May only be used in The Loop.
 *
 * A link will be placed on each page of the menu, except if the page is in category 'groupeur' of the
 * 'sofadp_tags' taxonomy.
 *
 * @param string $elt_relation 'top', 'siblings' or 'children'. Elements to include in the menu relatively to the current post.
 * @param int $depth 0 means no limits.
 * @param array $templates The following templates may be overriden: start_lvl, end_lvl, start_el, end_el, static_wrapper and link_wrapper.
 *          To read defaults, see MenuWalker::templates in classes/MenuWalker.php
 * @param string $exclude All pages excluded
 */
function get_menu($elt_relation = 'top', $depth = 0, $templates = [], $exclude) {

    $post = get_post();
    if(!$post) {
        return;
    }

    $root_id = 0;

    //determine root
    switch($elt_relation) {
        case 'top':
            $ancestor = $post;
            while(0 != $ancestor->post_parent) {
                $ancestor = get_post($ancestor->post_parent);
            }
            $root_id = $ancestor->ID;
            break;

        case 'top-theme':
            $ancestors = get_ancestors( $post->ID, 'page' );
            $root_id = $ancestors[1];
            break;

        case 'top-theme-sp':
            $ancestors = get_ancestors( $post->ID, 'page' );
            $root_id = $ancestors[2];
            break;

        case 'top-theme-ancestor':
            $ancestors = get_ancestors( $post->ID, 'page' );
            $root_id = $ancestors[3];
            break;

        case 'siblings':
            $root_id = $post->post_parent;
            break;

        case 'children':
            $root_id = $post->ID;
            break;

    }

  /*  if(0 == $root_id) {
        return;
    }*/

    $menu_elts = get_pages([
        'child_of' => $root_id, //actually returns all descendants of $root_id
        'sort_column' => 'menu_order',
        'exclude' => $exclude
    ]);

    get_page_hierarchy($menu_elts);

    $walker = new MenuWalker($templates);

    $html_menu = $walker->walk($menu_elts, $depth, ['max_depth' => $depth]);

    echo $html_menu;
}



/**
 * Echoes a link to the previous page.
 *
 * @param int $ancestor_lvl Number of parent levels that may be traversed in order to find a previous page, if the current page is the first child of its parent. 1 means only immediate siblings are considered. 0 means no limit.
 * @param string $disabled_wrapper HTML wrapper used when no previous page exists.
 * @param string $link_wrapper HTML wrapper used when a previous page exists.
 * @return void|\WP_Error
 */
function get_previous($ancestor_lvl = 1, $disabled_wrapper = '<span>Précédent</span>', $link_wrapper = '<a href="%s" title="%s">Précédent</a>') {
    $post = get_post();
    if(!$post) {
        return;
    }

    // Find list of pages this button navigates through

    if(0 == $ancestor_lvl) {
        $ancestor_lvl = 999;
    }

    $ancestor = $post;
    for($ii = 0; $ii < $ancestor_lvl && 0 != $ancestor->post_parent; ++$ii) {
        $ancestor = get_post($ancestor->post_parent);
    }
    $root_id = $ancestor->ID;

    if(0 == $root_id) {
        return;
    }

    $menu_elts = get_pages([
        'child_of' => $root_id, //actually returns all descendants of $root_id
        'sort_column' => 'menu_order'
    ]);

    get_page_hierarchy($menu_elts);


    //Filter out dummy pages

    $menu_elts = array_filter($menu_elts, function($post) {
        return !has_term('groupeur', 'sofadp_tags', $post);
    });


    // Cycle through pages, looking for current page

    foreach($menu_elts as $ii => $elt) {
        if($elt->ID == $post->ID) {
            if(0 == $ii) {
                //This is the first page: there is no previous page.
                echo $disabled_wrapper;

            } else {
                //Previous page exists.
                $previous_post = $menu_elts[$ii - 1];
                echo sprintf($link_wrapper,
                             get_permalink($previous_post),
                             $previous_post->post_title);
            }
            return;
        }
    }

    return new \WP_Error('sofad_unexpected_error', __("Can't find current page.", 'sofadmini'));
}



/**
 * Echoes a link to the next page.
 *
 * @param int $ancestor_lvl Number of parent levels that may be traversed in order to find a next page, if the current page is the last child of its parent. 1 means only immediate siblings are considered. 0 means no limit.
 * @param string $disabled_wrapper HTML wrapper used when no next page exists.
 * @param string $link_wrapper HTML wrapper used when a next page exists.
 * @return void|\WP_Error
 */
function get_next($ancestor_lvl = 1, $disabled_wrapper = '<span>Suivant</span>', $link_wrapper = '<a href="%s" title="%s">Suivant</a>')
{
    $post = get_post();
    if (!$post) {
        return;
    }

    // Find list of pages this button navigates through

    if (0 == $ancestor_lvl) {
        $ancestor_lvl = 999;
    }

    $ancestor = $post;
    for ($ii = 0; $ii < $ancestor_lvl && 0 != $ancestor->post_parent; ++$ii) {
        $ancestor = get_post($ancestor->post_parent);
    }
    $root_id = $ancestor->ID;

    if (0 == $root_id) {
        return;
    }

    $menu_elts = get_pages([
        'child_of' => $root_id, //actually returns all descendants of $root_id
        'sort_column' => 'menu_order'
    ]);

    get_page_hierarchy($menu_elts);


    // Filter out dummy pages

    $menu_elts = array_filter($menu_elts, function ($post) {
        return !has_term('groupeur', 'sofadp_tags', $post);
    });

    $menu_elts = array_values($menu_elts);

    // Cycle through pages, looking for current page

    foreach ($menu_elts as $ii => $elt) {

        // Found the current page!
        if ($elt->ID == $post->ID) {

            if ((count($menu_elts) - 1) == $ii) {
                //This is the last page: there is no next page.
                $disabled_wrapper;

            } else {
                //Next page exists.
                //$next_post = $menu_elts[$ii + 1];
                $next_post = ($menu_elts[$ii + 1] != "" ? $menu_elts[$ii + 1] : $menu_elts[$ii + 2]);

                echo sprintf($link_wrapper,
                    get_permalink($next_post),
                    $next_post->post_title);
            }
            return TRUE;
        }
    }

    return new \WP_Error('sofad_unexpected_error', __("Can't find current page.", 'sofadmini'));
}

/**
 * Echoes a link to the next section's page.
 *
 * @param int $ancestor_lvl Number of parent levels that may be traversed in order to find a next page, if the current page is the last child of its parent. 1 means only immediate siblings are considered. 0 means no limit.
 * @param string $disabled_wrapper HTML wrapper used when no next page exists.
 * @param string $link_wrapper HTML wrapper used when a next page exists.
 * @return void|\WP_Error
 */
function get_next_section($disabled_wrapper = '<span>Suivant</span>', $link_wrapper = '<a href="%s" title="%s">Suivant</a>')
{
    $post = get_post();
    if (!$post) {
        return;
    }

    $ancestors = get_post_ancestors( $post );

    $menu_elts = get_pages([
        'child_of' => $ancestors[1],
        'sort_column' => 'menu_order',
        'parent' => $ancestors[1]
    ]);

    //print_r($menu_elts);

    foreach ($menu_elts as $ii => $elt) {
      if ($elt->ID == $ancestors[0]) {
        if ((count($menu_elts) - 1) == $ii) {

          //augmenter d'un niveau
          $menu_elts_a = get_pages([
              'child_of' => $ancestors[2],
              'sort_column' => 'menu_order',
              'parent' => $ancestors[2]
          ]);

          foreach ($menu_elts_a as $jj => $elt_a) {
            if ($elt_a->ID == $ancestors[1]) {

              if ((count($menu_elts_a) - 1) == $jj) {
                //echo "blabla";
              }else{

                $args = array(
                	'child_of' => $menu_elts_a[$jj+1]->ID,
                  'sort_column' => 'menu_order'
                );

                $children = get_pages($args);
                $display_name_child = $menu_elts_a[$jj+1];
                $first_child = $children[0];
                //echo $first_child->ID;

                if(has_term('groupeur', 'sofadp_tags', $first_child)){
                  $is_groupeur = true;
                }

                if($is_groupeur){
                  $display_name_child = $first_child;
                  $args_2 = array(
                  	'child_of' => $first_child->ID,
                    'sort_column' => 'menu_order'
                  );
                  $children_temp = get_pages($args_2);
                  $first_child = $children_temp[0];
                }
                echo sprintf($link_wrapper,
                    get_permalink($first_child),
                    $display_name_child->post_title);

              }
            }
          }

        }else{
          $args = array(
          	'child_of' => $menu_elts[$ii+1]->ID,
            'sort_column' => 'menu_order'
          );
          $children = get_pages($args);

          if (!empty($children)){
            $first_child = $children[0];
          }else{
            $first_child = $menu_elts[$ii+1];
          }

          $next_post = $menu_elts[$ii+1];

          echo sprintf($link_wrapper,
              get_permalink($first_child),
              $next_post->post_title);
        }
      }
    }

    return new \WP_Error('sofad_unexpected_error', __("Can't find current page.", 'sofadmini'));
}



/**
 * Echoes an ID based URI for the current page that will not change if the slug is modified.
 */
function get_id_url() {
    echo get_site_url() . '/index.php?page_id=' . get_the_ID();
}



/**
 * Is the page being rendered for exportation? Use this function to hide development information.
 *
 * @return bool
 */
function is_launching() {
    return array_key_exists('sofadauteur_launch', $_REQUEST);
}



/**
 * Renders glossary terms and their definitions from SOFADauteur's glossary.
 *
 * @param string $begin First letter from range of terms to be rendered.
 * @param string $end Letter to stop rend at. (ex: 'F' means stop at words starting with 'F'. 'Foo' will not be rendered.)
 * @param string $html_template
 */
function get_glossary_range($begin, $end=NULL, $html_template="<dt>%s</dt>\n<dd>%s</dd>\n") {
    $definitions = \sofadauteur\controllers\glossary_list::get_range($begin, $end);

    foreach ($definitions as $definition) {
        echo sprintf($html_template, $definition->post_title, $definition->post_content);
    }
}
