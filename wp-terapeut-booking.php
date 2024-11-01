<?php

/**
 * @package WP_TerapeutBooking
 * @version 3.3.1
 */
/*
Plugin Name: WP Terapeut Booking
Plugin URI: http://wordpress.org/plugins/wp-terapeut-booking/
Description: This plugin makes it possible to place a link to your Online Booking page anywhere on your website by inserting [wpterapeut].
Author: Terapeut Booking
Domain Path: /languages
Version: 3.3.1
Author URI: http://www.terapeutbooking.dk
*/

/*  Copyright 2013  Terapeut Booking - Bo Møller  (email : bo@terapeutbooking.dk)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function terapeutbooking_func($atts)
{

    //Plugins

    $clicktext = get_option('wpterapeut_clicktext');
    $booklink = get_option('wpterapeut_booklink');

    if (isset($atts['link'])) {
        if ($atts['link'] != "") {
            $booklink = $atts['link'];
        }
    }

    if (isset($atts['text'])) {
        if ($atts['text'] != "") {
            $clicktext = $atts['text'];
        }
    }

    $bookscript = '<script src="https://system.easypractice.net/online_book/onlinebook.js?url=https%3A%2F%2Fsystem.easypractice.net&affix=/book/' . rawurlencode($booklink) . '&txt=' . rawurlencode($clicktext) . '" id="terapeutbookingonlinebooking" ></script>';

    return $bookscript;
}
add_shortcode('wpeasypractice', 'terapeutbooking_func');
add_shortcode('wpterapeut', 'terapeutbooking_func');


// create custom plugin settings menu
add_action('admin_menu', 'terapeutbooking_create_menu');

function terapeutbooking_create_menu()
{

    //create new top-level menu
    add_menu_page(__('EasyPractice Plugin Settings', 'wp-terapeut-booking'), __('EasyPractice..', 'wp-terapeut-booking'), 'administrator', __FILE__, 'terapeutbooking_settings_page', plugins_url('/img/wpterapeut_small.png', __FILE__));

    //call register settings function
    add_action('admin_init', 'register_mysettings');
}


function register_mysettings()
{
    //register our settings
    register_setting('terapeutbooking-settings-group', 'clicktext');
    register_setting('terapeutbooking-settings-group', 'link');
}

function terapeutbooking_settings_page()
{
?>
    <?php
    if (isset($_POST["update_settings"])) {
        // Saving the clicktext
        $clicktext = esc_attr($_POST["wpterapeut_clicktext"]);
        update_option("wpterapeut_clicktext", $clicktext);

        // Saving the book link
        $booklink = esc_attr($_POST["wpterapeut_booklink"]);
        update_option("wpterapeut_booklink", $booklink);

    ?>
        <div id="message" class="updated"><?php _e('Settings saved', 'wp-terapeut-booking'); ?></div>

    <?php
    }
    ?>

    <div class="wrap">
        <h2><?php _e('EasyPractice', 'wp-terapeut-booking'); ?></h2>
        <p>
            <?php _e('WP EasyPractice is a plugin that allows you to conveniently link your <a href="https://easypractice.net">EasyPractice</a> Online Booking page to your WordPress site.', 'wp-terapeut-booking'); ?>
        </p>
        <p><?php _e(
                'The plugin is now active. Now when you insert [wpterapeut] on a page, the plugin will replace [wpterapeut] with a link to your Online Booking page.',
                'wp-terapeut-booking'
            ); ?>
        </p>
        <p>
            <?php _e('When the user clicks on the link, they will be shown your Online Booking site in a nice form.', 'wp-terapeut-booking'); ?>
        </p>

        <hr>

        <form method="post" action="">

            <h2><?php _e('Link text', 'wp-terapeut-booking'); ?></h2>
            <p><?php _e('Insert the text to be used as a link to the booking form. For example, "Click here to book an appointment".', 'wp-terapeut-booking'); ?></p>
            <input type="text" style="width:600px;" name="wpterapeut_clicktext" value="<?php echo get_option('wpterapeut_clicktext', __('Click here to book an appointment.', 'wp-terapeut-booking')); ?>" />

            <h2><?php _e('Address', 'wp-terapeut-booking'); ?></h2>
            <p><?php _e('Insert the last part of the address for your booking form exactly as it appears in EasyPractice.', 'wp-terapeut-booking'); ?></p>
            <p><?php _e('For example, if your address looks like this <span style="font-style:italic;">https://system.easypractice.net/book/terapeuten-bo</span> you must insert <span style="font-style:italic">terapeuten-bo</span> below.', 'wp-terapeut-booking'); ?>
            </p>
            <input type="text" style="width:600px;" name="wpterapeut_booklink" value="<?php echo get_option('wpterapeut_booklink', __('Insert your own link here.', 'wp-terapeut-booking')); ?>" />

            <input type="hidden" name="update_settings" value="Y" />
            <?php submit_button(); ?>

        </form>
    </div>
<?php } ?>
<?php

function ap_action_init()
{
    // Localization
    load_plugin_textdomain('wp-terapeut-booking', false, dirname(plugin_basename(__FILE__)) . "/languages");
}

// Add actions
add_action('init', 'ap_action_init');

?>