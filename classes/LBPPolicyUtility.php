<?php

class LBPPolicyUtility {
	public static function getContentCached( $lbp_policy_url ) {
		$cache_key        = md5( $lbp_policy_url . wp_salt() );
		$cache_expiration = 60 * 60 * 24 * 1; //seconds
		$lines_string     = get_transient( $cache_key );

		if ( false === $lines_string ) {
			$lines_string = wp_remote_retrieve_body( wp_remote_get( $lbp_policy_url ) );

			//output, you can also save it locally on the server
			set_transient( $cache_key, $lines_string, $cache_expiration );
		}

		return $lines_string;
	}

	public static function clearCache( $lbp_policy_url ) {
		$cache_key = md5( $lbp_policy_url . wp_salt() );
		delete_transient( $cache_key );
	}

	public static function isSecure() {
		return
			( ! empty( $_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] !== 'off' )
			|| $_SERVER['SERVER_PORT'] === 443;
	}

}