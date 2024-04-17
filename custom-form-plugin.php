<?php
/*
Plugin Name: Custom Form Plugin
Description: Adds a custom form shortcode with AJAX functionality.
Version: 1.0
Author: Gazi Akter
*/

class Custom_Form_Plugin {

    public function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_ajax_save_data', array($this, 'save_data'));
        add_action('wp_ajax_nopriv_save_data', array($this, 'save_data')); // For non-logged in users
        add_shortcode('custom_form', array($this, 'shortcode'));
    }

    // Enqueue scripts and styles
    public function enqueue_scripts() {
        wp_enqueue_script('custom-form-script', plugin_dir_url(__FILE__) . 'assets/js/custom-form-script.js', array('jquery'), '1.0', true);
    }

    // AJAX callback function
    public function save_data() {
        // Check nonce
        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'save_data_nonce' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }

        if (isset($_POST['formData'])) {
            parse_str($_POST['formData'], $formData); // Parse form data

            // Sanitize and validate form data (example)
            $name = sanitize_text_field($formData['name']);
            $email = sanitize_email($formData['email']);

            // Example: Save data to database
            global $wpdb;
            $table_name = $wpdb->prefix . 'custom_table';

            $wpdb->insert($table_name, array(
                'name' => $name,
                'email' => $email
            ));

            // Send response back to the client
            wp_send_json_success( 'Data saved successfully!' );
        }

        // If nonce check fails or form data is missing
        wp_send_json_error( 'Error saving data' );

        wp_die(); // Always include this at the end to terminate script execution
    }

    // Shortcode function
    public function shortcode() {
        ob_start(); // Start output buffering
        ?>
        <form id="dataForm">
            <input type="text" name="name" placeholder="Name">
            <input type="email" name="email" placeholder="Email">
            <?php wp_nonce_field( 'save_data_nonce', 'save_data_nonce' ); ?> <!-- Add nonce field -->
            <button type="submit">Submit</button>
        </form>
        <?php
        return ob_get_clean(); // Return the buffered content
    }
}

new Custom_Form_Plugin();
