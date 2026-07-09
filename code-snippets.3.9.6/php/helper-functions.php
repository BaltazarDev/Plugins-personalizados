<?php
/**
 * Helper functions for page-specific code snippet execution.
 *
 * @package Code_Snippets
 */

/**
 * Perform early match against page restrictions using requested URL.
 * Used during plugins_loaded hook.
 *
 * @param string $pages_string Comma-separated page restrictions.
 * @return bool True if matched or empty, false if not matched.
 */
function code_snippets_match_page_restrictions_early( string $pages_string ): bool {
	$pages_string = trim( $pages_string );
	if ( empty( $pages_string ) ) {
		return true;
	}

	$pages = array_filter( array_map( 'trim', explode( ',', $pages_string ) ) );
	if ( empty( $pages ) ) {
		return true;
	}

	$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
	$request_path = wp_parse_url( $request_uri, PHP_URL_PATH );
	$request_path = '/' . trim( $request_path, '/' ) . '/';

	foreach ( $pages as $page ) {
		// If it looks like a URL path (starts/ends with / or has /) or wildcard
		if ( strpos( $page, '/' ) !== false || strpos( $page, '*' ) !== false ) {
			$page_path = '/' . trim( $page, '/' ) . '/';
			if ( strpos( $page_path, '*' ) !== false ) {
				$pattern = str_replace( '*', '.*', preg_quote( $page_path, '#' ) );
				if ( preg_match( '#^' . $pattern . '$#', $request_path ) ) {
					return true;
				}
			} elseif ( $request_path === $page_path || strpos( $request_uri, $page ) !== false ) {
				return true;
			}
		}
	}

	return false;
}

/**
 * Perform late match against page restrictions using WordPress page properties (IDs and Slugs).
 * Used during wp hook.
 *
 * @param string $pages_string Comma-separated page restrictions.
 * @return bool True if matched, false otherwise.
 */
function code_snippets_match_page_restrictions_late( string $pages_string ): bool {
	$pages_string = trim( $pages_string );
	if ( empty( $pages_string ) ) {
		return true;
	}

	$pages = array_filter( array_map( 'trim', explode( ',', $pages_string ) ) );
	if ( empty( $pages ) ) {
		return true;
	}

	$current_id = get_the_ID();

	// Get current page/post slug
	$current_slug = '';
	if ( is_singular() ) {
		global $post;
		if ( $post ) {
			$current_slug = $post->post_name;
		}
	} elseif ( is_category() || is_tag() || is_tax() ) {
		$queried_object = get_queried_object();
		if ( $queried_object && isset( $queried_object->slug ) ) {
			$current_slug = $queried_object->slug;
		}
	}

	foreach ( $pages as $page ) {
		// Numeric ID check
		if ( is_numeric( $page ) && intval( $page ) === $current_id ) {
			return true;
		}
		// Slug check
		if ( ! empty( $current_slug ) && strtolower( $page ) === strtolower( $current_slug ) ) {
			return true;
		}
	}

	// Fallback to URL-based checking
	return code_snippets_match_page_restrictions_early( $pages_string );
}

/**
 * Check if the snippet matches the page/post being edited in the admin dashboard.
 *
 * @param string $pages_string Comma-separated page restrictions.
 * @return bool True if matched or empty, false otherwise.
 */
function code_snippets_match_page_restrictions_admin( string $pages_string ): bool {
	$pages_string = trim( $pages_string );
	if ( empty( $pages_string ) ) {
		return true;
	}

	$pages = array_filter( array_map( 'trim', explode( ',', $pages_string ) ) );
	if ( empty( $pages ) ) {
		return true;
	}

	// Identify the post ID from GET or POST params
	$post_id = 0;
	if ( isset( $_GET['post'] ) ) {
		$post_id = intval( $_GET['post'] );
	} elseif ( isset( $_POST['post_ID'] ) ) {
		$post_id = intval( $_POST['post_ID'] );
	}

	if ( $post_id > 0 ) {
		$post = get_post( $post_id );
		foreach ( $pages as $page ) {
			// ID match
			if ( is_numeric( $page ) && intval( $page ) === $post_id ) {
				return true;
			}
			// Slug match
			if ( $post && strtolower( $page ) === strtolower( $post->post_name ) ) {
				return true;
			}
		}
	}

	// Default in admin area: if page-restricted, don't execute unless on that specific edit screen
	return false;
}
