<?php

namespace sofad\theme\mini;

/**
 * Class MenuWalker
 * @package sofad\theme\mini
 *
 * Renders an list of parent related WP_Post instances into a HTML menu hierarchy.
 *
 * A link will be placed on each page of the menu, except if the page is in category 'groupeur' of the
 * 'sofadp_tags' taxonomy.
 */
class MenuWalker extends \Walker {
    public $db_fields = [
        'parent' => 'post_parent',
        'id' => 'ID'
    ];

    private $templates = [
        'start_lvl' => '<ul>',
        'end_lvl' => '</ul>',
        'start_el' => '<li class="%s">',
        'end_el' => '</li>',
        'static_wrapper' => '<span>%2$s</span>',
        'link_wrapper' => '<a href="%s" class="%4$s">%s</a>',
        'class_current_el' => 'active',
        'class_current_link' => 'current'
    ];

    private $counters = [];

    public function __construct($templates = []) {
        $this->templates = array_merge($this->templates, $templates);
        $this->counters[] = 0;
        $this->current = get_post();

        $ancestor = $this->current;
        $this->active_ids = [ $ancestor->ID ];
        while(0 != $ancestor->post_parent) {
            $ancestor = get_post($ancestor->post_parent);
            $this->active_ids[] = $ancestor->ID;
        }
    }

    public function start_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= $this->templates['start_lvl'];
        $this->counters[] = 0;
    }

    public function end_lvl( &$output, $depth = 0, $args = array() ) {
        $output .= $this->templates['end_lvl'];
        array_pop($this->counters);
    }

    public function start_el( &$output, $object, $depth = 0, $args = array(), $current_object_id = 0 ) {
        $index = ++$this->counters[count($this->counters) - 1];


        $actual_content = $object;
        $wrapper = $this->templates['link_wrapper'];

        if(has_term('groupeur', 'sofadp_tags', $object)) {
            //if max depth and is grouper, drill down for actual content child
            if($args['max_depth'] == $depth + 1) {
                $actual_content = null;

                $children = get_pages([
                    'child_of' => $object->ID,
                    'sort_column' => 'menu_order'
                ]);

                get_page_hierarchy($children);

                foreach($children as $child) {
                    if(!has_term('groupeur', 'sofadp_tags', $child)) {
                        $actual_content = $child;
                        break;
                    }
                }

                if(is_null($actual_content)) {
                    $wrapper = $this->templates['static_wrapper'];
                }
            } else {
                $wrapper = $this->templates['static_wrapper'];
            }
        }

        //Mark ancestor menus as active
        $is_current = in_array($object->ID, $this->active_ids);

        $output .=
            sprintf($this->templates['start_el'], ($is_current ? $this->templates['class_current_el'] : '')) .
            sprintf($wrapper,
                get_permalink($actual_content),
                $object->post_title,
                $index,
                ($is_current ? $this->templates['class_current_link'] : ''));
    }

    public function end_el( &$output, $object, $depth = 0, $args = array() ) {
        $output .= $this->templates['end_el'];
    }
}