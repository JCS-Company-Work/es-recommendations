<?php

namespace EsRecommendations;

use WP_Query;

class ESRE_Compare {

    public function __construct() {
		add_shortcode( 'recommendations', [ $this, 'display_recommendations' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
	}

	/**
	 * Register the JS (but don't enqueue globally).
	 */
	public function register_assets() {
		wp_register_script(
			'compare-recommendations',
			plugins_url( '/../assets/js/compare.js', __FILE__ ),
			[],
			ESRE_VERSION,
			true
		);
	}

    public function display_recommendations() {

        // Enqueue only when shortcode is rendered
		wp_enqueue_script( 'compare-recommendations' );

        // Get the 'ids' parameter from URL
        $ids_param = isset($_GET['ids']) ? sanitize_text_field($_GET['ids']) : '';

        if ( empty( $ids_param ) ) {
            echo '<p>No products selected for comparison.</p>';
            return;
        }

        // Split by comma into array of IDs
        $batch_ids = array_filter( array_map( 'intval', explode( ',', $ids_param ) ) );

        if ( empty( $batch_ids ) ) {
            echo '<p>No valid product IDs provided.</p>';
            return;
        }

        // Example WP_Query to get posts by ID
        $args = [
            'post_type'      => 'batch', // adjust if your post type is different
            'post__in'       => $batch_ids,
            'posts_per_page' => -1,
            'orderby'        => 'post__in', // preserve the order from URL
        ];

        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            echo '<ul class="compare-products">';
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<li>' . get_the_title() . ' (ID: ' . get_the_ID() . ')</li>';
            }
            echo '</ul>';
            wp_reset_postdata();
        } else {
            echo '<p>No products found for the given IDs.</p>';
        }
    }

}