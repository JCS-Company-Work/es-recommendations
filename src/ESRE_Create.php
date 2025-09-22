<?php

namespace EsRecommendations;

class ESRE_Create {

    public function __construct() {
		add_shortcode( 'create_recommendations', [ $this, 'render' ] );
		add_action( 'wp_enqueue_scripts', [ $this, 'register_assets' ] );
	}

	/**
	 * Register the JS (but don't enqueue globally).
	 */
	public function register_assets() {
		wp_register_script(
			'create-recommendations',
			plugins_url( '/../assets/js/create.js', __FILE__ ),
			[],
			ESRE_VERSION,
			true
		);
	}

    public function render() {

        // Enqueue only when shortcode is rendered
		wp_enqueue_script( 'create-recommendations' );

        ob_start(); ?>
        <form class="compare-form">
            <label for="ids">Enter product IDs (comma separated):</label><br>
            <input type="text" name="ids" id="ids" placeholder="12345,54321" required>
            <button type="submit">Build URL</button>
        </form>

        <div id="built-url" style="margin-top:10px;">
            <h4>Generated URL:</h4>
            <a href="#" target="_blank" id="compare-link"></a>
        </div>
        <?php
        return ob_get_clean();
    }

}