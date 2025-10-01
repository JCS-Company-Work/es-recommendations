<?php
namespace EsRecommendations;

use WP_Query;

/**
 * Handles building comparison data for recommended batches.
 *
 * Gathers product information based on a comma-separated list of IDs
 * passed via the URL, returning an array of structured item data.
 */
class ESRE_Compare {

    /**
     * Set up hooks and ensure tileSpecs class is loaded.
     */
    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

        // Load tileSpecs class once when the plugin is loaded.
        require_once get_stylesheet_directory() . '/classes/tileSpecs.php';
    }

    /**
     * Register the JavaScript needed for the comparison view.
     *
     * Script is only enqueued when display_recommendations() runs.
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

    /**
     * Build and return an array of comparison items.
     *
     * Reads a comma-separated list of IDs from the "ids" query string,
     * queries matching 'batch' posts, and assembles all relevant fields.
     *
     * @return array Structured product data ready for template output.
     */
    public function display_recommendations() {

        // Enqueue JS only when needed.
        wp_enqueue_script( 'compare-recommendations' );

        // Sanitize and validate the incoming IDs.
        $ids_param = isset( $_GET['ids'] ) ? sanitize_text_field( $_GET['ids'] ) : '';
        if ( empty( $ids_param ) ) {
            return [];
        }

        $batch_ids = array_filter( array_map( 'intval', explode( ',', $ids_param ) ) );
        if ( empty( $batch_ids ) ) {
            return [];
        }

        // Query matching batch posts.
        $query = new WP_Query( [
            'post_type'      => 'batch',
            'post__in'       => $batch_ids,
            'posts_per_page' => -1,
            'orderby'        => 'post__in',
        ] );

        $items = [];

        // Collect and format data for each post.
        if ( $query->have_posts() ) {

            while ( $query->have_posts() ) {

                $query->the_post();

                $id = get_the_ID();

                if ( ! class_exists( '\tileSpecs' ) ) {
                    require_once get_stylesheet_directory() . '/classes/tileSpecs.php';
                }

                $tileData  = new \tileSpecs( $id );
                $batchData = $tileData->batchesData();
                $product = wc_get_product( $id );

                $items[] = [
                    'id'                        => $id,
                    'permalink'                 => get_permalink(),
                    'title'                     => get_the_title(),
                    'effect'                    => $tileData->findEffect( $id ),
                    'gallery'                   => get_post_gallery() ? get_post_gallery( $id, false ) : null,
                    'swatches_first'            => true,
                    'colour'                    => strtolower( (string) get_field( 'colour' ) ),
                    'batch'                     => $batchData,
                    'stock'                     => $product->get_stock_quantity(),
                    'menu_order'                => (int) get_post_field( 'menu_order', $id ),
                    'discounted_carton_price'   => $tileData->discounted_carton_price,
                    'sqm'                       => $tileData->sqm,
                    'vatRate'                   => $tileData->vatRate,
                    'single_carton_price'       => $tileData->single_carton_price,
                    'price_per_piece'           => get_field('price_per_piece') ? true : false,

                ];
            }
            wp_reset_postdata();
        }

        return $items;
    }
}