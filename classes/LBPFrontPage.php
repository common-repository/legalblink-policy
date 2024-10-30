<?php

class LBPFrontPage {
	private $pattern_wrapper = '#[pattern]#is';
	private $pattern_iframe = '<iframe.*?\/iframe>';
	private $pattern_script = '<script.*?\/script>';
	private $pattern_embed = '<embed.*?>';
	private $pattern = '';
	private $value = '';
	public $js_array = array();

	public $known_scripts = array(
		'apis.google.com/js/api.js',
		'cse.google.com/cse.js',
		'googletagmanager.com/gtm.js',
		'loader.engage.gsfn.us/loader.js',
		'headwayapp.co/widget.js',
		'wchat.freshchat.com',
		'widget.uservoice.com',
		'UserVoice.push',
		'static.olark.com/jsclient/loader0.js',
		'cdn.elev.io',
		'paypalobjects.com/js/external/api.js',
		'paypalobjects.com/api/checkout.js',
		'apis.google.com/js/plusone.js',
		'apis.google.com/js/client/plusone.js',
		'apis.google.com/js/platform.js',
		'www.youtube.com/iframe_api',
		'youtu.be',
		'platform.twitter.com/widgets.js',
		'instawidget.net/js/instawidget.js',
		'disqus.com/embed.js',
		'platform.linkedin.com/in.js',
		'pinterest.com/js/pinit.js',
		'codepen.io',
		'addthis.com/js/',
		'bat.bing.com',
		'sharethis.com/button/buttons.js',
		'scorecardresearch.com/beacon.js',
		'neodatagroup.com',
		'lp4.io',
		'cdn.optimizely.com/js/',
		'cdn.segment.io/analytics.js',
		'cdn.segment.com/analytics.js',
		'i.kissmetrics.com/i.js',
		'cdn.mxpnl.com',
		'rum-static.pingdom.net/prum.min.js',
		'googlesyndication.com/pagead/js/adsbygoogle.js',
		'googlesyndication.com/pagead/show_ads.js',
		'googleadservices.com/pagead/conversion.js',
		'www.googletagmanager.com/gtag/js',
		'window.adsbygoogle',
		'static.ads-twitter.com',
		'connect.facebook.net',
		'static.criteo.net/js/',
		'adagionet.com/uploads/js/sipra.js',
		'cdn-wx.rainbowtgx.com/rtgx.js',
		'outbrain.js',
		's.adroll.com',
		'scdn.cxense.com',
	);

	/**
	 * Checks if the current request is a WP REST API request.
	 *
	 * Case #1: After WP_REST_Request initialisation
	 * Case #2: Support "plain" permalink settings
	 * Case #3: It can happen that WP_Rewrite is not yet initialized,
	 *          so do this (wp-settings.php)
	 * Case #4: URL Path begins with wp-json/ (your REST prefix)
	 *          Also supports WP installations in subfolders
	 *
	 * @returns boolean
	 * @author matzeeable
	 */
	public function is_rest() {
		$prefix = rest_get_url_prefix();
		if ( ( defined( 'REST_REQUEST' ) && REST_REQUEST ) // (#1)
		     || ( isset( $_GET['rest_route'] ) // (#2)
		          && strpos( trim( $_GET['rest_route'], '\\/' ), $prefix, 0 ) === 0 ) ) {
			return true;
		}
		// (#3)
		global $wp_rewrite;
		if ( $wp_rewrite === null ) {
			$wp_rewrite = new WP_Rewrite();
		}

		// (#4)
		$rest_url    = wp_parse_url( trailingslashit( rest_url() ) );
		$current_url = wp_parse_url( add_query_arg( array() ) );

		return strpos( $current_url['path'], $rest_url['path'], 0 ) === 0;
	}

	public function __construct() {
		// add_action('shutdown', array(&$this, 'lbp_shutdown_debug'));
		if ( ! is_admin() && ! wp_doing_ajax() && ! $this->is_rest() ) {
			add_action( 'wp_head', array( $this, 'bufferBodyStart' ), 1000000 );
			add_action( 'shutdown', array( $this, 'bufferBodyEnd' ),
				- 1000000 ); // wp_print_footer_scripts // wp_footer
		}
	}

	public function lbp_shutdown_debug() {
		var_dump( $GLOBALS['wp_actions'] );
		foreach ( $GLOBALS['wp_actions'] as $action => $count ) {
			printf( '%s (%d) <br/>' . PHP_EOL, $action, $count );
		}
	}

	private function in_array_match( $value, $array ) {
		foreach ( $array as $k => $v ) {
			if ( strpos( $value, trim( $v ) ) !== false ) {
				return true;
			}
		}

		return false;
	}

	public function bufferBodyStart() {
		if ( ob_get_contents() ) {
			ob_end_flush();
		}
		ob_start();
	}

	public function bufferBodyEnd() {
		$buffer = ob_get_contents();
		if ( ob_get_contents() ) {
			ob_end_clean();
		}

		if ( isset( $_COOKIE['lbp_cookie_accepted'] ) && $_COOKIE['lbp_cookie_accepted'] === 'yes' ) {
			echo $buffer;
		} else {
			// Detect pattern by configuration
			$lbp_tab_advanced_settings_section_advanced_settings = AdminPageFramework::getOption( 'LBPSettingsPage',
				'lbp_tab_advanced_settings_section_advanced_settings' );

			$patterns = array();
			if ( isset( $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_iframe'] ) &&
			     (int) $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_iframe'] === 1 ) {
				$patterns[] = $this->pattern_iframe;
			}

			$is_filter_scripts = false;
			$pattern_scripts   = str_replace( '[pattern]', $this->pattern_script, $this->pattern_wrapper );
			if ( isset( $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_scripts'] ) &&
			     (int) $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_scripts'] === 1 ) {
				// $patterns[] = $this->pattern_script;
				$is_filter_scripts = true;
			}

			if ( isset( $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_embed'] ) &&
			     (int) $lbp_tab_advanced_settings_section_advanced_settings['lbp_advanced_settings_auto_block_embed'] === 1 ) {
				$patterns[] = $this->pattern_embed;
			}

			if ( ! empty( $patterns ) ) {
				$this->pattern = str_replace( '[pattern]', implode( '|', $patterns ), $this->pattern_wrapper );

				if ( $is_filter_scripts ) {
					preg_match( "/(.*)(<body.*)/s", $buffer, $matches );
					$head = ( isset( $matches[1] ) ) ? $matches[1] : '';
					$body = ( isset( $matches[2] ) ) ? $matches[2] : '';

					preg_match_all( $pattern_scripts, $body, $body_matches );
					if ( ! empty( $body_matches[0] ) ) {
						foreach ( $body_matches[0] as $k => $v ) {
							if ( $this->in_array_match( trim( $v ), $this->known_scripts ) ) {
								$body             = preg_replace( '/' . str_replace( preg_quote( "<---------LBP--------->" ),
										".*",
										preg_quote( trim( $v ), '/' ) ) . '/is', $this->value, $body );
								$this->js_array[] = trim( $v );
							}
						}
					}

					$buffer = $head . $body;
				}

				preg_match( "/(.*)(<body.*)/s", $buffer, $matches );
				$head = ( isset( $matches[1] ) ) ? $matches[1] : '';
				$body = ( isset( $matches[2] ) ) ? $matches[2] : '';
				preg_match_all( $this->pattern, $body, $body_matches );
				if ( ! empty( $body_matches[0] ) ) {
					foreach ( $body_matches[0] as $k => $v ) {
						$body             = preg_replace( '/' . str_replace( preg_quote( "<---------LBP--------->" ),
								".*",
								preg_quote( trim( $v ), '/' ) ) . '/is', $this->value, $body );
						$this->js_array[] = trim( $v );
					}
				}

				$buffer_new = $head . $body;
				echo '<!-- LBPStartBody -->' . $buffer_new . '<!-- LBPEndBody -->';
			} else {
				echo $buffer;
			}
		}
	}
}