<?php
/*
Plugin Name: Easy Image Gallery - Extend Photoswipe
Plugin URI: http://www.markdelorey.com
Description: Extends Easy Image Gallery for Photoswipe http://photoswipe.com
Version: 0.1
Author: Mark Delorey
Author URI: http://www.markdelorey.com
License: GPL-2.0+
License URI: http://www.opensource.org/licenses/gpl-license.php
*/

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'EIG_EXTEND_VERSION_PSWP' ) )
	define( 'EIG_EXTEND_VERSION_PSWP', '0.1' );

if ( ! defined( 'EIG_EXTEND_PLUGIN_URL_PSWP' ) )
	define( 'EIG_EXTEND_PLUGIN_URL_PSWP', plugin_dir_url( __FILE__ ) );

/**
 * Add new options to the select menu on the settings -> media page
 * @param  array $lightboxes 
 * @return array $lightboxes
 */
function eig_extend_easy_image_gallery_lightbox_pswp( $lightboxes ) {
    $lightboxes['photoswipe'] 	= 'Photoswipe';

    return $lightboxes;
}
add_filter( 'easy_image_gallery_lightbox', 'eig_extend_easy_image_gallery_lightbox_pswp' );

/**
 * Enqueue the required scripts for the lightboxes
 * @return void
 */
function eig_pswp_extend_easy_image_gallery_scripts() {	
   	$lightbox = function_exists( 'easy_image_gallery_get_lightbox' ) ? easy_image_gallery_get_lightbox() : '';
   	
   	if( 'photoswipe' == $lightbox ) {
	   	wp_enqueue_script( 'photoswipe', EIG_EXTEND_PLUGIN_URL_PSWP . '/includes/photoswipe/photoswipe.min.js', array(), EIG_EXTEND_VERSION_PSWP, true );
	   	wp_enqueue_script( 'photoswipe-ui', EIG_EXTEND_PLUGIN_URL_PSWP . '/includes/photoswipe/photoswipe-ui-default.min.js', array( 'photoswipe' ), EIG_EXTEND_VERSION_PSWP, true );
   		wp_enqueue_style( 'photoswipe-styles', EIG_EXTEND_PLUGIN_URL_PSWP . '/includes/photoswipe/photoswipe.css', '', EIG_EXTEND_VERSION_PSWP, '' );
   		wp_enqueue_style( 'photoswipe-default-skin', EIG_EXTEND_PLUGIN_URL_PSWP . '/includes/photoswipe/default-skin/default-skin.css', array( 'photoswipe-styles' ), EIG_EXTEND_VERSION_PSWP, '' );
   	}
   	
}
add_action( 'easy_image_gallery_scripts', 'eig_pswp_extend_easy_image_gallery_scripts' );

/**
 * Load the required JS in the footer
 *
 * Easy Image Gallery has an action hook within the wp_footer. Adding the script to the 'easy_image_gallery_js' hook below will ensure that the lightbox script is loaded correctly only if we're on a singular page and the images are linked to larger versions
 * @return [type] [description]
 */
function eig_pswp_extend_easy_image_gallery_js() { 
	$lightbox = function_exists( 'easy_image_gallery_get_lightbox' ) ? easy_image_gallery_get_lightbox() : '';
		
	if ( 'photoswipe' == $lightbox ) { ?>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				
				var galleryThumbnails = $('.image-gallery li');
				
				var galleryItems	=	[];
				
				galleryThumbnails.each(function(index, el){
					
					var sel	=	$(el).children('a');
					
					var galleryItem	=	{
						src: sel.attr('href'),
						w: 	sel.attr('data-width'),
						h: sel.attr('data-height')
					};
					
					galleryItems.push(galleryItem);
					
				});
				
				var pswpElement = document.querySelectorAll('.pswp')[0];
				
				$('.image-gallery li > a').on('click', function(e){
					
					// define options (if needed)
					var options = {
					    index: galleryThumbnails.index($(this).parent()),
					    bgOpacity: .85,
					    tapToClose: true
					};
				
					// Initializes and opens PhotoSwipe
					var pswpGallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, galleryItems, options);
					pswpGallery.init();
					
					e.preventDefault();
				});
				
			});
		</script>
	<?php	
	}
}
add_action( 'easy_image_gallery_js', 'eig_pswp_extend_easy_image_gallery_js' );