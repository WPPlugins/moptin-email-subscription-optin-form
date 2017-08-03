<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function call_Moptin_Class() {
    new moptin_meta_box_Class();
}
 
if ( is_admin() ) {
    add_action( 'load-post.php',     'call_Moptin_Class' );
    add_action( 'load-post-new.php', 'call_Moptin_Class' );
}
 
/**
 * The Class.
 */
class moptin_meta_box_Class {
 
    /**
     * Hook into the appropriate actions when the class is constructed.
     */
    public function __construct() {
        add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
        add_action( 'save_post',      array( $this, 'save'         ) );
    }
 
    /**
     * Adds the meta box container.
     */
    public static function defaults(){
        return [
            "meta_box"=>[
                "title"=>[
                    "subheading"=>"Subheadng",
                    "form"=>"Email Signup Form Code",
                    "colors"=>"Colors",
                    "extra"=>'Extra Details',
                    "shortcode"=>'Shortcode',
                    "demo"=>"Demo"
                ]
            ]
        ];
    }
    public function add_meta_box( $post_type ) {
        // Limit meta box to certain post types.
        $post_types = array('moptin');
        $defaults = $this->defaults()["meta_box"];
        if ( in_array( $post_type, $post_types ) ) {
            add_meta_box(
                'moptin_subheading',
                __( $defaults["title"]["subheading"], 'moptin' ),
                array( $this, 'render_subheading_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
            add_meta_box(
                'moptin_form',
                __( $defaults["title"]["form"], 'moptin' ),
                array( $this, 'render_form_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
            add_meta_box(
                'moptin_colors',
                __( $defaults["title"]["colors"], 'moptin' ),
                array( $this, 'render_colors_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
            add_meta_box(
                'moptin_extra',
                __( $defaults["title"]["extra"], 'moptin' ),
                array( $this, 'render_extra_meta_box_content' ),
                $post_type,
                'side',
                'high'
            );
            add_meta_box(
                'moptin_shortcode',
                __( $defaults["title"]["shortcode"], 'moptin' ),
                array( $this, 'render_shortcode_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
            add_meta_box(
                'moptin_demo',
                __( $defaults["title"]["demo"], 'moptin' ),
                array( $this, 'render_demo_meta_box_content' ),
                $post_type,
                'advanced',
                'high'
            );
        }
    }
 
    /**
     * Save the meta when the post is saved.
     *
     * @param int $post_id The ID of the post being saved.
     */
    public function save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['moptin_inner_custom_box_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['moptin_inner_custom_box_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'moptin_inner_custom_box' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        // Check the user's permissions.
        if ( 'moptin' == $_POST['post_type'] ) {
            if ( ! current_user_can( 'edit_page', $post_id ) ) {
                return $post_id;
            }
        } else {
            if ( ! current_user_can( 'edit_post', $post_id ) ) {
                return $post_id;
            }
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $subheading = sanitize_text_field( $_POST['moptin_subheading'] );
 
        $form = html_entity_decode($_POST['moptin_form'] );
        $type = sanitize_text_field($_POST['moptin_type'] );
        $img_size = sanitize_text_field($_POST['moptin_img_size'] );
        $img_align = sanitize_text_field($_POST['moptin_img_align'] );
        $boxed = sanitize_text_field($_POST['moptin_boxed'] );
        $bg_color = sanitize_text_field( $_POST['moptin_bg_color'] );
        $bg_color_opacity = sanitize_text_field( $_POST['moptin_bg_color_opacity'] );
        $input_bg_color = sanitize_text_field( $_POST['moptin_input_bg_color'] );
        $input_color = sanitize_text_field( $_POST['moptin_input_color'] );
        $text_color = sanitize_text_field( $_POST['moptin_text_color'] );
        $btn_color = sanitize_text_field( $_POST['moptin_btn_color'] );
        $btn_text_color = sanitize_text_field( $_POST['moptin_btn_text_color'] );
        $placeholder_color = sanitize_text_field( $_POST['moptin_placeholder_color'] );
        $imgC_width = sanitize_text_field( $_POST['moptin_img-container_width'] );
        $conC_width = sanitize_text_field( $_POST['moptin_content-container_width'] );
        $cookie = sanitize_text_field($_POST['moptin_cookie']);
        $input__border_radius__width = sanitize_text_field($_POST['moptin__input__border_radius__width']);
        $input__border_width = sanitize_text_field($_POST['moptin__input__border_width']);
        $input__border_color = sanitize_text_field($_POST['moptin_input__border_color']);
        $display_condition = sanitize_text_field($_POST['moptin_display_condition']);
        // Update the meta field.
        update_post_meta( $post_id, 'subheading', $subheading );
        update_post_meta( $post_id, 'type', $type );
        update_post_meta( $post_id, 'boxed', $boxed );
        update_post_meta( $post_id, 'cookie', $cookie );
        update_post_meta( $post_id, 'bg_color', $bg_color );
        update_post_meta( $post_id, 'bg_color_opacity', $bg_color_opacity );
        update_post_meta( $post_id, 'input_bg_color', $input_bg_color );
        update_post_meta( $post_id, 'input_color', $input_color );
        update_post_meta( $post_id, 'text_color', $text_color );
        update_post_meta( $post_id, 'btn_color', $btn_color );
        update_post_meta( $post_id, 'placeholder_color', $placeholder_color );
        update_post_meta( $post_id, 'btn_text_color', $btn_text_color );
        update_post_meta( $post_id, 'form', $form );
        update_post_meta( $post_id, 'img_size', $img_size );
        update_post_meta( $post_id, 'img_align', $img_align );
        update_post_meta( $post_id, 'imgC_width', $imgC_width );
        update_post_meta( $post_id, 'conC_width', $conC_width );
        update_post_meta($post_id,'input__border_radius__width',$input__border_radius__width);
        update_post_meta($post_id,'input__border_width',$input__border_width);
        update_post_meta($post_id,'input__border_color',$input__border_color);
        update_post_meta($post_id,'display_condition',$display_condition);
    }
 
 
    /**
     * Render Meta Box content.
     *
     * @param WP_Post $post The post object.
     */
    public function select_box($value,$array){
        $return = "";
        foreach($array as $k=>$v){
                       $return .= "<option value='{$k}' ".(($k==$value) ? 'selected' : '').">{$v}</option>";
                   }
                   return $return;
    }
    public function render_subheading_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'moptin_inner_custom_box', 'moptin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $subheading = get_post_meta( $post->ID, 'subheading', true );
 
        // Display the form, using the current value.
        ?>
            <textarea id="moptin_subheading" name="moptin_subheading" rows="2" style="width:100%"><?php echo esc_attr( $subheading ); ?></textarea>
        <?php
    }
    public function render_extra_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'moptin_inner_custom_box', 'moptin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $img_align = get_post_meta( $post->ID, 'img_align', true );
        $img_size = get_post_meta( $post->ID, 'img_size', true );
        $boxed = get_post_meta( $post->ID, 'boxed', true );
        $imgC_width = get_post_meta( $post->ID, 'imgC_width', true );
        $conC_width = get_post_meta( $post->ID, 'conC_width', true );
        $type = get_post_meta( $post->ID, 'type', true );
        $cookie = get_post_meta( $post->ID, 'cookie', true );
        $input__border_radius__width = get_post_meta($post->ID,'input__border_radius__width',true);
        $input__border_width = get_post_meta($post->ID,'input__border_width',true);
        $display_condition = get_post_meta($post->ID,'display_condition',true);
        // Display the form, using the current value.
        ?>
            <table>
                <tr>
                    <td><label for="moptin_img_size"><?php _e("Image Size","moptin"); ?></label></td>
                    <td>
                        <select name="moptin_img_size" id="moptin_img_size">
                            <?php
                               $img_sizes = array("thumbnail"=>"Thumbnail","medium"=>"300x300","large"=>"Large","full"=>"Original Size");
                               echo $this->select_box($img_size, $img_sizes)
                            ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><label for="moptin_img_align"><?php _e("Image Align","moptin"); ?></label></td>
                    <td>
                        <select name="moptin_img_align" id="moptin_img_align">
                            <?php
                               $img_aligns = array("left"=>"Left","right"=>"Right","top"=>"Above Heading");
                               echo $this->select_box($img_align,$img_aligns);
                            ?>
                        </select>
                        <script>
                            document.getElementById("moptin_img_align").onchange = function(){
                                var imgC_width = document.getElementById("moptin_img-container_width"),
                                    conC_width = document.getElementById("moptin_content-container_width");
                                if(this.value=="top"){
                                    imgC_width.value=100;
                                    conC_width.value=100;
                                }else if((this.value=="left" || this.value=="right") && (imgC_width.value=="100" || conC_width.value=="100")){
                                    imgC_width.value=30;
                                    conC_width.value=70;
                                }
                        }
                    </script>
                    </td>
                </tr>
                <tr>
                    <td><label for="moptin_boxed"><?php _e("Boxed","moptin"); ?></label></td>
                    <td><input type="checkbox" name="moptin_boxed" id="moptin_boxed" value="moptin-box" <?php echo (!empty($boxed)) ? "checked" : ""; ?>/></td>
                </tr>
                <tr>
                    <td><label for="moptin_cookie"><?php _e("Cookie","moptin"); ?></label></td>
                    <td><input type="checkbox" name="moptin_cookie" id="moptin_cookie" value="moptin-cookie" <?php echo (!empty($cookie)) ? "checked" : ""; ?>/></td>
                </tr>
                <tr>
                    <td><label for="moptin_img-container_width"><?php _e("Image Box Width","moptin"); ?></label></td>
                    <td><input type="number" name="moptin_img-container_width" id="moptin_img-container_width" size="3" min="0" max="100" onkeyup="document.getElementById('moptin_content-container_width').value=100-this.value;" value="<?php echo (isset($imgC_width) ?  $imgC_width : 30); ?>"/>%</td>
                </tr>
                <tr>
                    <td><label for="moptin_content-container_width"><?php _e("Content Box Width","moptin"); ?></label></td>
                    <td><input type="number" name="moptin_content-container_width" size="3" id="moptin_content-container_width" min="0" max="100" onkeyup="document.getElementById('moptin_img-container_width').value=100-this.value;" value="<?php echo (isset($conC_width) ?  $conC_width : 70); ?>"/>%</td>
                </tr>
                <tr>
                    <td><label for="moptin_type"><?php _e("Type","moptin"); ?></label></td>
                    <td>
                        <select name="moptin_type" id="moptin_type">
                            <?php
                               $types = array("moptin-inline"=>"Inline","moptin-takeover"=>"Page Takeover");
                               echo $this->select_box($type,$types);
                            ?>
                        </select>
                        <script>
                            var moptin_type = document.getElementById("moptin_type");
                            moptin_type.onchange = function(){
                                moptin_display_condition_tr(this);
                            }
                            function moptin_display_condition_tr(e){
                                var tr = document.getElementById('moptin_display_condition_tr');
                                if(e.value==='moptin-takeover'){ 
                                    tr.style.display='table-row'
                                } else {
                                    tr.style.display='none'
                                }
                            }
                        </script>
                    </td>
                </tr>
                <tr id="moptin_display_condition_tr" style="display: none;">
                    <td><label for="moptin_display_condition"><?php _e("When to Display?","moptin"); ?></label></td>
                    <td>
                        <select name="moptin_display_condition" id="moptin_display_condition">
                            <?php
                               $display_conditions = array(
                                   "moptin__display__immediately"=>"Immediately",
                                   "moptin__display__delay-5"=>"After 5 Seconds",
                                   "moptin__display__delay-10"=>"After 10 Seconds",
                                   "moptin__display__scroll-little"=>"After Little Scroll",
                                   "moptin__display__scroll-middle"=>"After Scrolled to Middle",
                                   "moptin__display-exit"=>"When user is about to Exit"
                                   );
                               echo $this->select_box($display_condition,$display_conditions);
                            ?>
                        </select>
                        <script>moptin_display_condition_tr(moptin_type);</script>
                    </td>
                </tr>
                <tr>
                    <td><label for="moptin__input__border_radius__width"><?php _e("Border Radius Width","moptin"); ?></label></td>
                    <td><input type="number" name="moptin__input__border_radius__width" size="2" id="moptin__input__border_radius__width" min="0" value="<?php echo ((isset($input__border_radius__width)) ?  $input__border_radius__width : "40"); ?>"/>px</td>
                </tr>
                <tr>
                    <td><label for="moptin__input__border_width"><?php _e("Border Width","moptin"); ?></label></td>
                    <td><input type="number" name="moptin__input__border_width" size="2" id="moptin__input__border_width" min="0" value="<?php echo ((isset($input__border_width)) ?  $input__border_width : 5); ?>"/>px</td>
                </tr>
            </table>
        <?php
    }
    public function render_form_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'moptin_inner_custom_box', 'moptin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $form = get_post_meta( $post->ID, 'form', true );
 
        // Display the form, using the current value.
        ?>
            <textarea id="moptin_form" name="moptin_form" rows="10" style="width:100%"><?php echo  $form ; ?></textarea>
            <br/><br/>
            <style>pre {
    background: #f3f3f7;
    border: 1px solid #dedee3;
    padding: 11px;
    font-size: 12px;
    line-height: 1.3em;
    margin-bottom: 22px;
    overflow: auto;
}
</style>
<strong>For Example:</strong>
            <pre><?php echo "&lt;form action=\"#\" <strong style='color:red'>class=\"moptin__form\"</strong>&gt;\n\n\t\t&lt;input type=\"text\" <strong style='color:red'>class=\"moptin__input\"</strong> placeholder=\"Your Name\" id=\"optin__name\"/&gt;
           \n\t\t&lt;input type=\"text\" <strong style='color:red'>class=\"moptin__input\"</strong> placeholder=\"Your Email\" id=\"optin__email\"/&gt;
           \n\t\t&lt;input type=\"submit\" <strong style='color:red'>class=\"moptin__submit-btn\"</strong> value=\"I'm In\" id=\"optin_submit\"/&gt;\n
           &lt;/form&gt;"; ?></pre><br/><br/>
<strong>Note</strong>: Use your Creativity here. You can include custom css/javascript.
If you don't want to include Email Sign up Form, then you can include your custom call-to-action text and button.
        <?php
    }
    public function render_shortcode_meta_box_content( $post ) {
 
        // Use get_post_meta to retrieve an existing value from the database.
        $post_id = $post->ID;
 
        // Display the form, using the current value.
        ?>
            <textarea id="moptin_shortcode" rows="2" style="width:100%">[moptin id="<?php echo $post_id; ?>"]</textarea>
        <?php
    }
    public function render_colors_meta_box_content( $post ) {
 
        // Add an nonce field so we can check for it later.
        wp_nonce_field( 'moptin_inner_custom_box', 'moptin_inner_custom_box_nonce' );
 
        // Use get_post_meta to retrieve an existing value from the database.
        $bg_color = get_post_meta( $post->ID, 'bg_color', true );
        $bg_color_opacity = get_post_meta( $post->ID, 'bg_color_opacity', true );
        $placeholder_color = get_post_meta( $post->ID, 'placeholder_color', true );
        $text_color = get_post_meta( $post->ID, 'text_color', true );
        $btn_color = get_post_meta( $post->ID, 'btn_color', true );
        $btn_text_color = get_post_meta( $post->ID, 'btn_text_color', true );
        $input_color = get_post_meta( $post->ID, 'input_color', true );
        $input_bg_color = get_post_meta( $post->ID, 'input_bg_color', true );
        $input__border_color = get_post_meta( $post->ID, 'input__border_color', true );
        
        // Display the form, using the current value.
        ?>
            <table>
                <tr>
                    <td>
                        <label for="moptin_bg_color">
                            <?php _e("Background Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($bg_color) ? esc_attr($bg_color) : "#0fcb80"; ?>" name="moptin_bg_color" id="moptin_bg_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_bg_color_opacity">
                            <?php _e("Background Color Opacity","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="number" min="0" max="1" step="0.01" value="<?php echo ($bg_color_opacity) ? esc_attr($bg_color_opacity) : 1; ?>" name="moptin_bg_color_opacity" id="moptin_bg_color_opacity"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_text_color">
                            <?php _e("Text Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($text_color) ? esc_attr($text_color) : "#ffffff"; ?>" name="moptin_text_color" id="moptin_text_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_btn_color">
                            <?php _e("Button Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($btn_color) ? esc_attr($btn_color) : "#ffffff"; ?>" name="moptin_btn_color" id="moptin_btn_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_btn_text_color">
                            <?php _e("Button Text Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($btn_text_color) ? esc_attr($btn_text_color) : "#0fcb80"; ?>" name="moptin_btn_text_color" id="moptin_btn_text_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_palceholder_color">
                            <?php _e("Placeholder Text Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($placeholder_color) ? esc_attr($placeholder_color) : "#fff"; ?>" name="moptin_placeholder_color" id="moptin_placeholder_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_input_color">
                            <?php _e("Input Text Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($input_color) ? esc_attr($input_color) : "#fff"; ?>" name="moptin_input_color" id="moptin_input_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_input_bg_color">
                            <?php _e("Input Background Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($input_bg_color) ? esc_attr($input_bg_color) : "#0fcb80"; ?>" name="moptin_input_bg_color" id="moptin_input_bg_color" class="moptin__color-field"/></td>
                </tr>
                <tr>
                    <td>
                        <label for="moptin_input__border_color">
                            <?php _e("Input Border Color","moptin"); ?>
                        </label>
                    </td>
                    <td><input type="text" value="<?php echo ($input__border_color) ? esc_attr($input__border_color) : "#0ca367"; ?>" name="moptin_input__border_color" id="moptin_input__border_color" class="moptin__color-field"/></td>
                </tr>
            </table>
        <?php
    }
    public function render_demo_meta_box_content( $post ) {
 
        // Use get_post_meta to retrieve an existing value from the database.
        $post_id = $post->ID;
 
        // Display the form, using the current value.
        if(isset($post_id) && !empty($post->ID)){
            echo "<style>#moptin-{$post_id} .moptin__f-wrapper{max-width:480px}</style>";
            echo do_shortcode("[moptin id='{$post_id}']");
        }else{
            echo "Save the Post";
        }
    }
}