<?php
namespace EsRecommendations;

use WP_Query;

/**
 * Handles building comparison data for recommended batches.
 *
 * Gathers product information based on a comma-separated list of IDs
 * passed via the URL (batchid query var), returning an array of structured item data.
 */
class ESRE_Compare {

    public function __construct() {
        add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );

        // Load tileSpecs class once
        require_once get_stylesheet_directory() . '/classes/tileSpecs.php';
    }

    /**
     * Register the JavaScript needed for the comparison view.
     */
    public function register_assets() {
        wp_register_script(
            'compare-recommendations',
            plugins_url( '/../assets/js/PricingCalculator.js', __FILE__ ),
            [],
            ESRE_VERSION,
            true
        );
        
        wp_register_script(
            'add-sample',
            plugins_url( '/../assets/js/AddSample.js', __FILE__ ),
            [],
            ESRE_VERSION,
            true
        );
    }

    /**
     * Private helper to get batch IDs from the URL.
     *
     * Supports both plain comma-separated and URL-encoded (%2C) IDs.
     *
     * @return int[] Array of batch IDs
     */
    private function get_batch_ids() {
        $batchid = get_query_var('batchid');
        if ( ! $batchid ) {
            return [];
        }

        // Decode URL-encoded string (%2C → ,)
        $batchid = urldecode($batchid);

        // Keep only digits and commas, then convert to integers
        return array_filter(
            array_map(
                'intval',
                explode(',', preg_replace('/[^0-9,]/', '', $batchid))
            )
        );
    }

    /**
     * Build and return an array of comparison items.
     */
    public function display_recommendations() {

        // Enqueue JS only when needed
        wp_enqueue_script( 'compare-recommendations' );
        wp_enqueue_script( 'add-sample' );

        $batch_ids = $this->get_batch_ids();
        if ( empty($batch_ids) ) {
            return []; // No IDs found, return empty array
        }

        // Query matching batch posts
        $query = new WP_Query([
            'post_type'      => 'batch',
            'post__in'       => $batch_ids,
            'posts_per_page' => -1,
            'orderby'        => 'post__in',
        ]);

        $items = [];

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $id = get_the_ID();

                if ( ! class_exists( '\tileSpecs' ) ) {
                    require_once get_stylesheet_directory() . '/classes/tileSpecs.php';
                }

                $tileData  = new \tileSpecs( $id );
                $batchData = $tileData->batchesData();
                $product   = wc_get_product( $id );

                $items[] = [
                    'id'                      => $id,
                    'permalink'               => get_permalink(),
                    'title'                   => get_the_title(),
                    'effect'                  => $tileData->findEffect( $id ),
                    'gallery'                 => get_post_gallery( $id, false ) ?: null,
                    'swatches_first'          => true,
                    'colour'                  => strtolower( (string) get_field( 'colour' ) ),
                    'batch'                   => $batchData,
                    'stock'                   => $product ? $product->get_stock_quantity() : 0,
                    'menu_order'              => (int) get_post_field( 'menu_order', $id ),
                    'discounted_carton_price' => $tileData->discounted_carton_price,
                    'sqm'                     => $tileData->sqm,
                    'vatRate'                 => $tileData->vatRate,
                    'single_carton_price'     => $tileData->single_carton_price,
                    'price_per_piece'         => get_field('price_per_piece') ? true : false,
                ];
            }
            wp_reset_postdata();
        }

        return $items;
    }
}