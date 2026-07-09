<?php
/**
 * Shortcode: [cr_media_header]
 * Muestra media principal SOLO para posts en categorías:
 * 53 (Centro de recursos), 55 (Webinars), 56 (Casos de éxito).
 *
 * Prioridad:
 * 1) Si al inicio del contenido hay video/embed -> mostrar video
 * 2) Imagen destacada
 * 3) Primera imagen del contenido
 */

if (!defined('ABSPATH')) {
	exit;
}

function cr_is_in_resource_categories($post_id) {
	$allowed_ids = array(53, 55, 56); // Centro de recursos, Webinars, Casos de éxito
	$cats = get_the_category($post_id);

	if (empty($cats) || is_wp_error($cats)) {
		return false;
	}

	foreach ($cats as $cat) {
		// Match directo de categoría
		if (in_array((int) $cat->term_id, $allowed_ids, true)) {
			return true;
		}

		// Match por ancestros (por si la entrada está en una hija de 53)
		$ancestors = get_ancestors((int) $cat->term_id, 'category');
		foreach ($ancestors as $ancestor_id) {
			if (in_array((int) $ancestor_id, $allowed_ids, true)) {
				return true;
			}
		}
	}

	return false;
}

function cr_get_leading_video_embed($post_id) {
	$content = get_post_field('post_content', $post_id);
	if (!$content) {
		return '';
	}

	// Gutenberg blocks (más confiable para "al inicio")
	if (function_exists('has_blocks') && has_blocks($content)) {
		$blocks = parse_blocks($content);

		foreach ($blocks as $block) {
			$html = isset($block['innerHTML']) ? trim($block['innerHTML']) : '';

			if (empty($block['blockName'])) {
				// Bloques sin nombre (clásico/HTML suelto): si traen contenido,
				// cuentan como inicio significativo para no detectar videos más abajo.
				if (!empty($html)) {
					if (preg_match('/^(?:<p>)?\s*(<iframe[\s\S]*?<\/iframe>|<video[\s\S]*?<\/video>)/i', $html, $m)) {
						return $m[1];
					}

					if (stripos($html, '<iframe') !== false || stripos($html, '<video') !== false) {
						// Hay video más abajo, pero no al inicio: no ocultar la imagen destacada.
						break;
					}

					$non_empty_unnamed = !empty(trim(wp_strip_all_tags($html)));
					if ($non_empty_unnamed) {
						break;
					}
				}

				continue;
			}

			$name = $block['blockName'];
			$attrs = isset($block['attrs']) ? $block['attrs'] : array();

			// Bloques de video/embed
			if ($name === 'core/video') {
				$rendered = render_block($block);
				if (!empty($rendered)) {
					return $rendered;
				}
			}

			if ($name === 'core/embed' || strpos($name, 'core-embed/') === 0) {
				if (!empty($attrs['url'])) {
					$oembed = wp_oembed_get($attrs['url']);
					if ($oembed) {
						return $oembed;
					}
				}
				$rendered = render_block($block);
				if (!empty($rendered)) {
					return $rendered;
				}
			}

			// Si pegaron iframe en HTML personalizado
			if ($name === 'core/html' && !empty($html)) {
				if (preg_match('/^(?:<p>)?\s*(<iframe[\s\S]*?<\/iframe>|<video[\s\S]*?<\/video>)/i', $html, $m)) {
					return $m[1];
				}
			}

			// URL suelta al inicio (p. ej. YouTube en párrafo)
			if ($name === 'core/paragraph' && !empty($html)) {
				$text = trim(wp_strip_all_tags($html));
				if (filter_var($text, FILTER_VALIDATE_URL)) {
					$oembed = wp_oembed_get($text);
					if ($oembed) {
						return $oembed;
					}
				}
			}

			// Si el primer bloque significativo NO es video/embed, no forzamos búsqueda más abajo.
			$non_empty = !empty(trim(wp_strip_all_tags($html)));
			$is_media_block = in_array($name, array('core/image', 'core/gallery', 'core/cover'), true);

			if ($non_empty || $is_media_block) {
				break;
			}
		}
	}

	// Fallback clásico: iframe/video en el inicio del contenido
	$trimmed = ltrim($content);
	if (preg_match('/^(?:<p>)?\s*(<iframe[\s\S]*?<\/iframe>|<video[\s\S]*?<\/video>)/i', $trimmed, $m)) {
		return $m[1];
	}

	return '';
}

function cr_get_first_content_image_html($post_id, $size = 'full') {
	$content = get_post_field('post_content', $post_id);
	if (!$content) {
		return '';
	}

	// Buscar primero en bloques Gutenberg de imagen
	if (function_exists('has_blocks') && has_blocks($content)) {
		$blocks = parse_blocks($content);
		foreach ($blocks as $block) {
			if (!empty($block['blockName']) && $block['blockName'] === 'core/image') {
				if (!empty($block['attrs']['id'])) {
					return wp_get_attachment_image((int) $block['attrs']['id'], $size, false, array('class' => 'cr-media-image'));
				}
				if (!empty($block['attrs']['url'])) {
					$url = esc_url($block['attrs']['url']);
					return '<img class="cr-media-image" src="' . $url . '" alt="">';
				}
			}
		}
	}

	// Fallback regex
	if (preg_match('/<img[^>]+>/i', $content, $m)) {
		return $m[0];
	}

	return '';
}

function cr_media_header_shortcode($atts = array()) {
	$atts = shortcode_atts(
		array(
			'only_resources' => '0',
		),
		$atts,
		'cr_media_header'
	);

	if (!is_singular()) {
		return '';
	}

	$post_id = get_the_ID();
	if (!$post_id) {
		return '';
	}

	if ($atts['only_resources'] === '1' && !cr_is_in_resource_categories($post_id)) {
		return '';
	}

	$video_html = cr_get_leading_video_embed($post_id);
	if (!empty($video_html)) {
		// Si hay video al inicio del contenido, usamos el del contenido y no mostramos hero.
		return '';
	}

	if (has_post_thumbnail($post_id)) {
		$img = get_the_post_thumbnail($post_id, 'full', array('class' => 'cr-media-image'));
		if (!empty($img)) {
			return '<div class="cr-media-hero cr-media-hero--image">' . $img . '</div>';
		}
	}

	$first_img = cr_get_first_content_image_html($post_id, 'full');
	if (!empty($first_img)) {
		return '<div class="cr-media-hero cr-media-hero--image">' . $first_img . '</div>';
	}

	return '';
}
add_shortcode('cr_media_header', 'cr_media_header_shortcode');

/**
 * Estilos base del media hero (video 16:9 + ajuste de imagen).
 */
function cr_media_hero_styles() {
	?>
	<style id="cr-media-hero-styles">
		.cr-media-hero {
			margin: 0 0 24px;
		}

		.cr-media-hero--video {
			position: relative;
			width: 100%;
			aspect-ratio: 16 / 9;
			overflow: hidden;
			background: #000;
		}

		.cr-media-hero--image {
			position: relative;
			width: 100%;
			overflow: visible;
			background: transparent;
		}

		.cr-media-hero--video iframe,
		.cr-media-hero--video video,
		.cr-media-hero--video .wp-video,
		.cr-media-hero--video .wp-block-embed__wrapper iframe {
			width: 100% !important;
			height: 100% !important;
			display: block;
			border: 0;
		}

		.cr-media-hero--image .cr-media-image,
		.cr-media-hero--image img {
			display: block;
			width: 100%;
			height: auto;
			object-fit: contain;
			object-position: center;
		}
	</style>
	<?php
}
add_action('wp_head', 'cr_media_hero_styles', 99);
