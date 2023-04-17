<?php
/**
 * GeneratePress.
 *
 * Please do not make any edits to this file. All edits should be done in a child theme.
 *
 * @package GeneratePress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Set our theme version.
define( 'GENERATE_VERSION', '3.1.3' );

if ( ! function_exists( 'generate_setup' ) ) {
	add_action( 'after_setup_theme', 'generate_setup' );
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since 0.1
	 */
	function generate_setup() {
		// Make theme available for translation.
		load_theme_textdomain( 'generatepress' );

		// Add theme support for various features.
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'quote', 'link', 'status' ) );
		add_theme_support( 'woocommerce' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style' ) );
		add_theme_support( 'customize-selective-refresh-widgets' );
		add_theme_support( 'align-wide' );
		add_theme_support( 'responsive-embeds' );

		$color_palette = generate_get_editor_color_palette();

		if ( ! empty( $color_palette ) ) {
			add_theme_support( 'editor-color-palette', $color_palette );
		}

		add_theme_support(
			'custom-logo',
			array(
				'height' => 70,
				'width' => 350,
				'flex-height' => true,
				'flex-width' => true,
			)
		);

		// Register primary menu.
		register_nav_menus(
			array(
				'primary' => __( 'Primary Menu', 'generatepress' ),
			)
		);

		/**
		 * Set the content width to something large
		 * We set a more accurate width in generate_smart_content_width()
		 */
		global $content_width;
		if ( ! isset( $content_width ) ) {
			$content_width = 1200; /* pixels */
		}

		// Add editor styles to the block editor.
		add_theme_support( 'editor-styles' );

		$editor_styles = apply_filters(
			'generate_editor_styles',
			array(
				'assets/css/admin/block-editor.css',
			)
		);

		add_editor_style( $editor_styles );
	}
}

/**
 * Get all necessary theme files
 */
$theme_dir = get_template_directory();

require $theme_dir . '/inc/theme-functions.php';
require $theme_dir . '/inc/defaults.php';
require $theme_dir . '/inc/class-css.php';
require $theme_dir . '/inc/css-output.php';
require $theme_dir . '/inc/general.php';
require $theme_dir . '/inc/customizer.php';
require $theme_dir . '/inc/markup.php';
require $theme_dir . '/inc/typography.php';
require $theme_dir . '/inc/plugin-compat.php';
require $theme_dir . '/inc/block-editor.php';
require $theme_dir . '/inc/class-typography.php';
require $theme_dir . '/inc/class-typography-migration.php';
require $theme_dir . '/inc/class-html-attributes.php';
require $theme_dir . '/inc/class-theme-update.php';
require $theme_dir . '/inc/class-rest.php';
require $theme_dir . '/inc/deprecated.php';

if ( is_admin() ) {
	require $theme_dir . '/inc/meta-box.php';
	require $theme_dir . '/inc/class-dashboard.php';
}

/**
 * Load our theme structure
 */
require $theme_dir . '/inc/structure/archives.php';
require $theme_dir . '/inc/structure/comments.php';
require $theme_dir . '/inc/structure/featured-images.php';
require $theme_dir . '/inc/structure/footer.php';
require $theme_dir . '/inc/structure/header.php';
require $theme_dir . '/inc/structure/navigation.php';
require $theme_dir . '/inc/structure/post-meta.php';
require $theme_dir . '/inc/structure/sidebars.php';



function resources_cpt_generating_rule($wp_rewrite) {
    $rules = array();
    $terms = get_terms( array(
        'taxonomy' => 'sistema',
        'hide_empty' => false,
    ) );
   
    $post_type = 'landing';

    foreach ($terms as $term) {    
                
        $rules['sistema/' . $term->slug . '/([^/]*)$'] = 'index.php?post_type=' . $post_type. '&landing=$matches[1]&name=$matches[1]';
                        
    }

    // merge with global rules
    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'resources_cpt_generating_rule');


function change_link( $permalink, $post ) {
    
    if( $post->post_type == 'landing' ) {
        $resource_terms = get_the_terms( $post, 'sistema' );
        $term_slug = '';
        if( ! empty( $resource_terms ) ) {
            foreach ( $resource_terms as $term ) {

                // The featured resource will have another category which is the main one
                if( $term->slug == 'featured' ) {
                    continue;
                }

                $term_slug = $term->slug;
                break;
            }
        }
        $permalink = get_home_url() ."/sistema/" . $term_slug . '/' . $post->post_name;
    }
    return $permalink;

}


//redirigir a area privada despues de iniciar sesion

add_filter('post_type_link',"change_link",10,2);

function my_custom_login_redirect(){

  wp_redirect( home_url("pagina-de-bienvenida") );

  exit();
}
add_action( 'wp_login','my_custom_login_redirect' );

// agregar shortcode de usuarios

add_shortcode('list-landing', 'shortcode_list_landing');

function shortcode_list_landing() {

	$args = array(
		'post_type' => 'landing',
		'author'        =>  get_current_user_id(), // I could also use $user_ID, right?        
		);
	$current_user_posts = get_posts( $args );
	if(empty($current_user_posts)){
		return;
	}
	?>

		<table class="main-table__landing">
			<thead>
				<tr>
				   <th>Landing</th>
				   <th width="300">Url</th>
				   <th width="150">F. de creación</th>
				   <th>Accion</th>
				</tr>
			</thead>
			<tbody>
			<?php for($i=0; $i<count($current_user_posts); $i++){
				  $id = $current_user_posts[$i]->ID;
				  $url = get_the_permalink($id); ?>
				<tr id="<?= get_the_terms( $id, 'sistema' )[0]->term_id; ?>"  data-id="<?= get_the_terms( $id, 'sistema' )[0]->term_id; ?>"> 
					<td  ><?= get_the_terms( $id, 'sistema' )[0]->name; ?> </td> 
					<td><a href="<?= $url; ?>" target="_blank"><?= $url; ?></a></td> 
					<td><?= get_the_date( 'D j M' , $id ); ?></td> 
					<td>
 						<div class="main-table__actions">
							<a class="btn" href="<?= $url; ?>" title="Ver" target="_blank"><i class="fa fa-eye" aria-hidden="true"></i></a>
							<a onclick="copiarAlPortapapeles(this)" title="Copiar" class="btn" data-url="<?= $url; ?>" ><i class="fa fa-clone" aria-hidden="true"></i></a>
							<a  title="Editar" href="#open-modal__edict" id="<?= $id; ?>" data-name="<?= get_the_terms( $id, 'sistema' )[0]->name; ?>" class="btn btn-pencil main-modal__edict" data-url="<?= $url; ?>" ><i class="fa fa-pencil-square" aria-hidden="true"></i></a>
							<a title="Borrar" href="#open-modal__trash" id="<?= $id; ?>" data-name="<?= get_the_terms( $id, 'sistema' )[0]->name; ?>" data-id-cat="<?= get_the_terms( $id, 'sistema' )[0]->term_id; ?>"   class="btn main-modal__trash" data-url="<?= $url; ?>" ><i class="fa fa-trash" aria-hidden="true"></i></a>
						</div>
					</td>
				</tr>
			<?php } ?>	
			</tbody>
			
        </table>
		
		<div id="open-modal__edict" class="modal-window-gf">
		  <div>
			<a href="#" title="Cerrar" class="modal-close-gf"><i class="fa fa-times" aria-hidden="true"></i></a>
			<h2 class="modal-edict__title"></h2>
			  <form>
				  <input type="text" id="updateInput" name="enlaceLanding" placeholder="Ingresa la nueva url">
				  <input type="hidden" value="" id="inputHidden" name="idLanding">
				  <button class="main-button__update" data-id="">
					  Actualizar
				  </button>
				  
			  </form>
		  </div>
		</div>
		<div id="open-modal__trash" class="modal-window-gf">
		  <div>
			<a href="#" title="Cerrar" class="modal-close-gf"><i class="fa fa-times" aria-hidden="true"></i></a>
			<h2 class="modal-trash__title">
			  Estas seguro que deseas eliminar
				<br>
				  <span class="modal-trash__title-span">

				  </span>
			  </h2>
			  
			  <div class="open-modal__btns">
				  <a href="#" class="open-modal__btnsa">
				  Cancelar
			  </a>
			  <button data-id=""  class="main-button__trash" >
				  Eliminar
			  </button>
			  </div>
			  
		  </div>
		</div>
		<style>
		
		</style>
		<script>
		
		function copiarAlPortapapeles(elemento) {
			console.log(elemento);

		  // Crea un campo de texto "oculto"
		  var aux = document.createElement("input");

		  // Asigna el contenido del elemento especificado al valor del campo
		  aux.setAttribute("value", elemento.getAttribute('data-url'));

		  // Añade el campo a la página
		  document.body.appendChild(aux);

		  // Selecciona el contenido del campo
		  aux.select();

		  // Copia el texto seleccionado
		  document.execCommand("copy");

		  // Elimina el campo de la página
		  document.body.removeChild(aux);

		}
		</script>
<?php
    
}




add_action('wp_header', 'my_icons');

function my_icons(){
	?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
<?php
}



add_action( 'wp_ajax_nopriv_my_trash', 'my_trash' );
add_action( 'wp_ajax_my_trash', 'my_trash' );
function my_trash(){
	$id = (int) $_POST['id'];
	$result = wp_delete_post( $id, true );
	return wp_send_json(['status' => $result, 'id_cat' => $_POST['id_cat'] ]);
}

add_action( 'wp_ajax_nopriv_my_update', 'my_update' );
add_action( 'wp_ajax_my_update', 'my_update' );
function my_update(){

  $my_post = array(
      'ID'           => $_POST['id'],
      'post_excerpt'   => $_POST['id_url'],
  );
 
	// Update the post into the database
  $result = wp_update_post( $my_post );
	return wp_send_json(['status' => $result, 'id_url' => $_POST['id_url'] ]);
}




add_action('wp_footer','my_password');

function my_password(){
	?>
<script>
	jQuery(document).ready(function(){
		jQuery('.elementor-lost-password').attr('href', '/pagina-de-recuperar-contrasena/');
	})
</script>
	
<?php
}



add_action('wp_footer', 'my_modals');

function my_modals(){
	?>
<script>
jQuery('.main-modal__edict').click(function(){
	let title = jQuery(this).attr('data-name');
	let id = jQuery(this).attr('id');
	let dataUrl = jQuery(this).attr('data-url');
	let modalTitle = jQuery('.modal-edict__title').text(title)
	let inputHidden = jQuery('#inputHidden').attr('value',id);
	let inputUpdate = jQuery('.main-button__update').attr('data-id',id);
	let inputUrl = jQuery('.main-button__update').attr('data-url',dataUrl);
})
	jQuery('.main-modal__trash').click(function(){
	let title = jQuery(this).attr('data-name');
	let id = jQuery(this).attr('id');
	let dataCat = jQuery(this).attr('data-id-cat');
	let modalTitle = jQuery('.modal-trash__title-span').text(title)
	let inputHidden = jQuery('.main-button__trash').attr('data-id',id);
    let inputDataCat = jQuery('.main-button__trash').attr('data-id-cat',dataCat);
})
	
	var items = new Array;
	jQuery('table tbody tr').each(function(){
		var optionTable = jQuery(this).attr('data-id');
		items.push(optionTable);
	})

	jQuery('#field_fsfpd option').each(function(){
		var optionSelect = jQuery(this).val();
		if(jQuery.inArray(optionSelect, items) >= 0 ){
			jQuery(this).prop('disabled', 'disabled');
		}
		
	})
	
	jQuery(function($){

    // craer formularios
    
        $( ".main-button__trash").click(function( event ) {
            event.preventDefault();
			$(this).addClass('disabled-button')
			$(this).text('Eliminando...')
            //url de enpoint
            let url = '<?php echo admin_url( 'admin-ajax.php' ); ?>'
            let id = $(this).attr('data-id');
			let idCat = $(this).attr('data-id-cat');
			
    
            //data enviada al endpoint
            let data = {
				'id_cat'		 : idCat,
                'id'			 : id,
                'restNonce'		 : '<?php wp_create_nonce( 'my-ajax-nonce' ); ?>',
				'action'		 : 'my_trash'
            };
    
           console.log(data);
			jQuery.post(url, data, function(response) {
    ;
                if(response['status'] != null){
					$(`#${response['id_cat']}`).remove();
					$('.modal-trash__title').text('Se ha eliminado correctamente')
					$('.main-button__trash').remove();
					$('.open-modal__btnsa').text('Ok');
				
					setTimeout(function(){
                    jQuery(location).prop('href', '/marketing-landing-page/')
                }, 1000)
				}
            });
    
        });
		
    });
	
	
	// update
	jQuery(function($){

    // craer formularios
    
        $( ".main-button__update").click(function( event ) {
            event.preventDefault();
			$(this).addClass('disabled-button')
			$(this).text('Actualizando...')
            //url de enpoint
            let url = '<?php echo admin_url( 'admin-ajax.php' ); ?>'
            let id = $(this).attr('data-id');
			let idUrl = $('#updateInput').val();
			
    
            //data enviada al endpoint
            let data = {
				'id_url'		 : idUrl,
                'id'			 : id,
                'restNonce'		 : '<?php wp_create_nonce( 'my-ajax-nonce' ); ?>',
				'action'		 : 'my_update'
            };
    
           console.log(data);
			jQuery.post(url, data, function(response) {
    ;
				console.log(response)
                if(response['status'] > 0){
					
					$('.modal-edict__title').text('Se ha actualizado correctamente')
					$('.main-button__update').text('Ok');
					$('#updateInput').remove();
					setTimeout(function(){
                    jQuery(location).prop('href', '/marketing-landing-page/')
                }, 1000)
				}
            });
    
        });
		
    });
	

	
</script>
<?php
}

add_action('wp_footer', 'my_excerpt');
function my_excerpt(){
	$url = !empty(get_the_excerpt()) ? get_the_excerpt() : '';
	?>
<script>
	var url = '<?php echo $url; ?>';
	if(url !== ''){
		jQuery('.assign-url-btn').each(function(){
			jQuery(this).find('a').attr('href', url);
		})
	}
	
</script>
<?php
	
}

//* NO incluyas la etiqueta de apertura
 
//* Enlace del menú "cerrar sesión"

add_filter( 'wp_nav_menu_objects', 'my_dynamic_menu_items' );

function my_dynamic_menu_items( $menu_items ) {
  $final_menu = [];
    foreach ( $menu_items as $menu_item ) {
        if ( '#salir#' == $menu_item->title ) {
             $menu_item->title = "Cerrar sesión";
			 $menu_item->url = wp_logout_url( home_url() );
             $final_menu[] = $menu_item;
        } else {
          $final_menu[] = $menu_item;
        }
    }
    return $final_menu;
}

add_action('template_redirect', 'redirect_g_to');

function redirect_g_to(){

	if(!is_user_logged_in()){
		return false;
	};
	$validate = [
		'expired',
		'cancelled',
	];
	if( redirect_payment_pendding() == 'pending' && !is_page('home') && !is_page('pago-pendiente')){

		wp_redirect( "/pago-pendiente/", 301 );
		exit();

	}else if(in_array(redirect_payment_pendding(), $validate, true) && !is_page('home') && !is_page('pagar-membresias')){

		wp_redirect( "/pagar-membresias/", 301 );
		exit();

	}else if(is_page('home') && !current_user_can('administrator') && redirect_payment_pendding() == 'active'){

	 	wp_redirect( "/pagina-de-bienvenida/", 301 );
	 	exit();

	 }	

}

 function redirect_payment_pendding(){

 	if(!is_user_logged_in()){
 		return false;
 	}

 	global $wpdb;

 	$table = $wpdb->prefix . 'rcp_memberships';

 	$id = get_current_user_id();

 	$sql = "SELECT $table.`status` FROM $table WHERE $table.`user_id` = $id";

 	$query = $wpdb->prepare( $sql );

 	$result = $wpdb->get_results( $query );
	
 	$status = !empty($result[count($result)-1]->status)? $result[count($result)-1]->status : false;
 	return $status;
 }
