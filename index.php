<?php
/**
 * Plugin Name: Moptin - Email Opt-in WordPress Plugin
 * Plugin URI: http://mycodingtricks.com
 * Description: Moptin is a Email Opt-in WordPress Plugin
 * Version: 2016.11.13.1
 * Author: Shubham Kumar
 * Author URI: http://mycodingtricks.com
 * Text Domain: moptin
 * Network: true
*/
include __DIR__.'/moptin-post-type.php';
include __DIR__.'/meta-box.php';

function load_Moptin(){
    wp_enqueue_script('moptin_js',plugins_url('moptin.js',__FILE__),array('jquery'),"2016.11.13.1");
    wp_enqueue_style('moptin_css',plugins_url('style.css',__FILE__),15,'2016.11.13.1');
    wp_enqueue_style('Montserrat','https://fonts.googleapis.com/css?family=Montserrat:700,400');
}
function moptin_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'moptin_admin', plugins_url( 'admin.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}

function moptin_shortcode($att){
    $post_id = (int) $att['id'];
    if(!isset($post_id) && empty($post_id)) return false;
    $cookie = get_post_meta($post_id,'cookie',true);
    if($_COOKIE["moptin-{$post_id}"]=="true" && isset($cookie) && !empty($cookie))  return false;
    $heading = get_the_title($post_id);
    $subheading = get_post_meta($post_id,"subheading",true);
    $bg_color = implode(",",hex2rgb(get_post_meta( $post_id, 'bg_color', true ),get_post_meta( $post_id, 'bg_color_opacity', true )));
    $text_color = get_post_meta( $post_id, 'text_color', true );
    $btn_color = get_post_meta( $post_id, 'btn_color', true );
    $input_bg_color = get_post_meta( $post_id, 'input_bg_color', true );
    $input_color = get_post_meta( $post_id, 'input_color', true );
    $placeholder_color = get_post_meta( $post_id, 'placeholder_color', true );
    $btn_text_color = get_post_meta( $post_id, 'btn_text_color', true );
    $img_size = get_post_meta( $post_id, 'img_size', true );
    $img_align = get_post_meta( $post_id, 'img_align', true );
    $form = get_post_meta($post_id,'form',true);
    $boxed = get_post_meta($post_id,'boxed',true);
    $imgC_width = get_post_meta($post_id,'imgC_width',true);
    $conC_width = get_post_meta($post_id,'conC_width',true);
    $type = get_post_meta($post_id,'type',true);
    $input__border_radius__width = get_post_meta($post_id,'input__border_radius__width',true);
    $input__border_width = get_post_meta($post_id,'input__border_width',true);
    $input__border_color = get_post_meta( $post_id, 'input__border_color', true );
    $display_condition = get_post_meta($post_id,'display_condition',true);
    
    $css = "<style>#moptin-{$post_id} .moptin__img-wrapper{width:{$imgC_width}%}#moptin-{$post_id} .moptin-credit>a{background:{$btn_color};color:{$btn_text_color}}  #moptin-{$post_id} .moptin__content-wrapper{width:{$conC_width}%}#moptin-{$post_id}.moptin{background:rgba({$bg_color});color:{$text_color}}#moptin-{$post_id} .moptin__submit-btn{background:{$btn_color};color:{$btn_text_color}}";
    $css .= "#moptin-{$post_id} .moptin__input::-webkit-input-placeholder{color:{$placeholder_color}}#moptin-{$post_id} .moptin__input:-moz-placeholder{color:{$placeholder_color}}#moptin-{$post_id} .moptin__input::-moz-placeholder{color:{$placeholder_color}}#moptin-{$post_id} .moptin__input:-ms-input-placeholder {color:{$placeholder_color}}";
    $css .= "#moptin-{$post_id} .moptin__input{background: {$input_bg_color};color:{$input_color};border-radius:{$input__border_radius__width}px;border-color:{$input__border_color};border-width:{$input__border_width}px}";
    $css .= "</style>";
    $js = "<script>(function($){\$('#moptin-{$post_id}').moptin().showOptin();})(jQuery);</script>";
    $code = '<div id="moptin-'.$post_id.'" class="moptin '.$cookie.' '.$display_condition.' '.$type.' moptin-'.$img_align.' moptin-green '.$boxed.' moptin-Montserrat moptin-t-center"> <div class="moptin__container"> <div class="moptin__img-wrapper"> '.get_the_post_thumbnail( $post_id,$img_size ,array('class'=>'moptin__img')) .' </div> <div class="moptin__content-wrapper"> <div class="moptin__h-wrapper"> <div class="moptin__heading"> '.$heading.' </div> <div class="moptin__sub-heading"> '.$subheading.' </div> </div> <div class="moptin__f-wrapper"> '.$form.'</div> </div> </div> <div class="moptin-credit"> Powered By <a href="http://mycodingtricks.com" target=_blank>My Coding Tricks</a></div></div>';
    return $css.$code.$js;
}
function hex2rgb($hex,$alpha=false){
 list($r, $g, $b) = (strlen($hex) === 4) ? sscanf($hex, "#%1x%1x%1x") : sscanf($hex, "#%2x%2x%2x");
 $rgb = array('r'=>$r,'g'=>$g,'b'=>$b);
    if($alpha){
        $rgb['a'] = $alpha;
    }
    return $rgb;
}
add_shortcode("moptin",  "moptin_shortcode");
add_action('wp_enqueue_scripts','load_Moptin');
add_action( 'admin_enqueue_scripts', 'moptin_add_color_picker' );
add_action( 'admin_enqueue_scripts', 'load_Moptin' );
// Enable shortcodes in text widgets
add_filter('widget_text','do_shortcode');
?>