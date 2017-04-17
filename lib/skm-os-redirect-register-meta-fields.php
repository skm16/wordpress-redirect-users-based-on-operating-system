<?php

	/**
	 * Calls the class on the post edit screen.
	 */
	function call_skmOsRedirect() {
	    new skmOsRedirect();
	}

	if ( is_admin() ) {
	    add_action( 'load-post.php',     'call_skmOsRedirect' );
	    add_action( 'load-post-new.php', 'call_skmOsRedirect' );
	}

	/**
	 * The Class.
	 */
	class skmOsRedirect {

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
	    public function add_meta_box( $post_type ) {
	        // Limit meta box to certain post types.
	        $post_types = array( 'page', 'post' );

	        if ( in_array( $post_type, $post_types ) ) {
	            add_meta_box(
	                'skm_os_redirects',
	                __( 'SKM OS Redirects', 'textdomain' ),
	                array( $this, 'skm_os_redirects_render_meta_box_content' ),
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
	        if ( ! isset( $_POST['skm_os_redirect_inner_custom_box_nonce'] ) ) {
	            return $post_id;
	        }

	        $nonce = $_POST['skm_os_redirect_inner_custom_box_nonce'];

	        // Verify that the nonce is valid.
	        if ( ! wp_verify_nonce( $nonce, 'skm_os_redirect_inner_custom_box' ) ) {
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
	        if ( 'page' == $_POST['post_type'] ) {
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
	        $skm_windows_redirect_data = sanitize_text_field( $_POST['skm_windows_redirect_field'] );
         $skm_mac_redirect_data = sanitize_text_field( $_POST['skm_mac_redirect_field'] );
         $skm_linux_redirect_data = sanitize_text_field( $_POST['skm_linux_redirect_field'] );

	        // Update the meta field.
	        update_post_meta( $post_id, '_skm_os_redirect_windows_redirect_value', $skm_windows_redirect_data );
         update_post_meta( $post_id, '_skm_os_redirect_mac_redirect_value', $skm_mac_redirect_data );
         update_post_meta( $post_id, '_skm_os_redirect_linux_redirect_value', $skm_linux_redirect_data );
	    }


	    /**
	     * Render Meta Box content.
	     *
	     * @param WP_Post $post The post object.
	     */
	    public function skm_os_redirects_render_meta_box_content( $post ) {

	        // Add an nonce field so we can check for it later.
	        wp_nonce_field( 'skm_os_redirect_inner_custom_box', 'skm_os_redirect_inner_custom_box_nonce' );

	        // Use get_post_meta to retrieve an existing value from the database.
	        $skm_windows_redirect_value = get_post_meta( $post->ID, '_skm_os_redirect_windows_redirect_value', true );
         $skm_mac_redirect_value = get_post_meta( $post->ID, '_skm_os_redirect_mac_redirect_value', true );
         $skm_linux_redirect_value = get_post_meta( $post->ID, '_skm_os_redirect_linux_redirect_value', true );

	        // Display the form, using the current value.
	        ?>
									<div id="skm-redirect-admin-instructions">
										<p>Enter the operating system redirects for this page or post in the fields below. If there are no operating system redirects just leave these fields blank.<br/><em>You must enter valid URLs including http:// or https://</em></p>
									</div>
	        <label for="skm_windows_redirect_field" style="font-weight:bold; font-size:14px; display:block; margin-bottom:5px;">
	            <?php _e( 'Redirect for Windows users:', 'textdomain' ); ?>
	        </label>
	        <input type="url" pattern="https?://.+" id="skm_windows_redirect_field" name="skm_windows_redirect_field" value="<?php echo esc_attr( $skm_windows_redirect_value ); ?>" style="display:block; margin-bottom:10px; width:100%;" />
         <label for="skm_mac_redirect_field" style="font-weight:bold; font-size:14px; display:block; margin-bottom:5px;">
	            <?php _e( 'Redirect for Mac users:', 'textdomain' ); ?>
	        </label>
	        <input type="url" pattern="https?://.+" id="skm_mac_redirect_field" name="skm_mac_redirect_field" value="<?php echo esc_attr( $skm_mac_redirect_value ); ?>" style="display:block; margin-bottom:10px; width:100%;" />
         <label for="skm_linux_redirect_field" style="font-weight:bold; font-size:14px; display:block; margin-bottom:5px;">
	            <?php _e( 'Redirect for Linux users:', 'textdomain' ); ?>
	        </label>
	        <input type="url" pattern="https?://.+" id="skm_linux_redirect_field" name="skm_linux_redirect_field" value="<?php echo esc_attr( $skm_linux_redirect_value ); ?>" style="display:block; width:100%;" />
	        <?php
	    }
	}
