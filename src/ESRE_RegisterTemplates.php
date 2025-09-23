<?php

namespace EsRecommendations;

class ESRE_RegisterTemplates {

    public function __construct() {

        add_filter( 'theme_page_templates', [ $this, 'add_template' ] );
        add_filter( 'template_include',     [ $this, 'load_template' ] );

    }

    public function add_template( $templates ) {

        $templates['recommendations.php'] = 'Recommendations';
var_dump($templates);
        return $templates;

    }

    public function load_template( $template ) {

        if ( is_page() && get_page_template_slug() === 'recommendations.php' ) {
            return plugin_dir_path( dirname( __FILE__ ) ) . 'templates/recommendations.php';
        }

        return $template;

    }
}
