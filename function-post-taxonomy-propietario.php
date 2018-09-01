<?php

include_once 'travel-tour-child-utils.php';

function load_admin_scripts()
{
    wp_register_style('style_admin', get_stylesheet_directory_uri() . '/assets/admin/css/admin-style.css', array(), '1.0.0', 'all');
//    wp_register_style('bootstrap', get_stylesheet_directory_uri() . '/admin/css/bootstrap.css', array(), '1.0.0', 'all');
//    //wp_register_style('bootstrapdate', get_stylesheet_directory_uri() . '/admin/css/bootstrap-datetimepicker.css', array(), '1.0.0', 'all');
    wp_enqueue_style('style_admin');


}

add_action('admin_enqueue_scripts', 'load_admin_scripts');

function post_admin_scripts()
{
    wp_enqueue_script('load-script', get_stylesheet_directory_uri() . '/assets/admin/js/admin-load.js', array(), '1.0.0', 'all');
    wp_enqueue_script('check-script', get_stylesheet_directory_uri() . '/assets/admin/js/admin-check.js', array(), '1.0.0', 'all');
    wp_enqueue_script('search-script', get_stylesheet_directory_uri() . '/assets/admin/js/admin-search.js', array(), '1.0.0', 'all');
    wp_localize_script('load-script', 'load', array(
        'ajaxurl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('load_security_key'),
    ));

    global $post;
    if (is_admin() && $post->post_type == 'propietario') {
        wp_enqueue_script('publish-activate', get_stylesheet_directory_uri() . '/assets/admin/js/activate-publish.js', array(), '1.0.0', 'all');
    }
}

add_action('admin_print_scripts-post.php', 'post_admin_scripts');
add_action('admin_print_scripts-post-new.php', 'post_admin_scripts');

add_action('wp_ajax_update_municipios', 'travel_tour_update_municipios');
add_action('wp_ajax_persona_search', 'travel_tour_search_persona');
add_action('wp_ajax_user_search', 'travel_tour_validate_user');

// the ajax function
add_action('wp_ajax_data_fetch', 'data_fetch');

add_action('wp_ajax_change_publish_status', 'change_publish_status');

add_action('wp_ajax_email_check', 'email_check');

add_action('wp_ajax_generate_password', 'generate_password');
function generate_password()
{
    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }
    $response->pass=wp_generate_password(20, false, true);
    echo json_encode($response);
    die();
}


/*For active / deactive save button*/
function change_publish_status()
{
    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }
    $validation = $_POST['validation'];
    if ($validation == 1) {

    }
    $response->message = true;
    echo json_encode($response);
    die();
}

function email_check()
{

    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }

    $email = $_POST['email'];
    if (email_exists($email)) {
        $response->result = true;
    } else {
        $response->result = false;
    }
    echo json_encode($response);
    wp_die();
}

/* For data search */
function data_fetch()
{
    $query = esc_attr($_POST['username']);
    if (!strlen($query)) {
        $response->message = false;
    } else {
        $query_args_meta = array(
            'orderby' => 'meta_value',
            'role' => 'subscriber',
            'meta_query' => array(
                'relation' => 'AND',
                array(
                    'key' => 'first_name',
                    'value' => sanitize_text_field($query),
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'last_name',
                    'value' => sanitize_text_field($query),
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'nickname',
                    'value' => sanitize_text_field($query),
                    'compare' => 'LIKE'
                )
            )
        );
        $users = new WP_User_Query($query_args_meta);
        $value = array();
        $count = 0;
        // User Loop
        if (!empty($users->get_results())) {
            foreach ($users->get_results() as $user) {
                //echo '<p>' . $user->display_name . '</p>';
                $value[$count] = array(
                    'name' => $user->display_name,
                    'id' => $user->ID,
                    'email' => $user->user_email,
                    'full_name' => $user->user_firstname . ' ' . $user->user_lastname,
                    'firstname' => $user->user_firstname,
                    'lastname' => $user->user_lastname
                );
                $count = $count + 1;
            }
            $response->message = true;
            $response->result = $value;
        } else {
            $response->message = false;
        }
    }

    echo json_encode($response);
    die();
    //wp_die();

}


function travel_tour_validate_user()
{
    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }

    $username = $_POST['username'];
    if (username_exists($username)) {
        $response->result = true;
    } else {
        $response->result = false;
    }

    echo json_encode($response);
    wp_die();
}


function travel_tour_search_persona()
{
    $data = esc_sql($_POST);
    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }

    $user = $data['user'];
    $render = "";
    switch ($user) {
        case 'nuevo':
            echo '<div id="propietario-data-username" class="data-form">';
            echo '<label for="travel_tour_propietario_data_username"><span class="dashicons dashicons-businessman"></span> </label>';
            echo '<input type="text" id="propietario_data_value_username" name="username" size="25" placeholder="Usuario" onkeyup="validateUser()" required/>';
            echo '<div id="loading-image" style="display: none"></div>';
            echo '<span id="message-user"></span>';
            echo '</div>';

            echo '<div id="propietario-data-password" class="data-form">';
            echo '<label for="travel_tour_propietario_data_password"><span class="dashicons dashicons-lock"></span> </label>';
            echo '<input type="password" id="propietario_data_value_password" name="password" size="25" placeholder="Password" value="' . wp_generate_password(20, false, true) . '" required/>';
            echo '<button id="show_password" type="button" class="button wp-hide-pw hide-if-no-js" onclick="changeVisibility()"><span class="dashicons dashicons-visibility"></span></button>';

            echo '</div>';

//            echo '<div id="propietario-data-password-check" class="data-form">';
//            echo '<label for="travel_tour_propietario_data_password_check"><span class="dashicons dashicons-lock"></span> </label>';
//            echo '<input type="password" id="propietario_data_value_password_check" name="confirm-password" placeholder="Password" onkeyup="validatePassword()"/>';
//            echo '<span id="message"></span>';
//            echo '</div>';

            die();

            break;
//        case 'existe':
//
//            break;
        default:
            die();
            break;
    }

}


function travel_tour_update_municipios()
{
    $data = esc_sql($_POST);

    if (!wp_verify_nonce($data['nonce'], 'load_security_key')) {
        wp_die('Security check');
    }

    $provincias = travel_tour_get_municipios_list();
    $provincia = $data['provincia'];
    $json = json_encode($provincias[$provincia]);
    echo $json;
    wp_die();
}




function travel_tour_propietario_add_meta_box()
{
    add_meta_box('propietario_data', 'Datos del propietario', 'travel_tour_propietario_data_callback', 'propietario', 'normal', 'high');
    add_meta_box('propietario_data_profile', 'Asociar con usuario', 'travel_tour_propietario_data_profile_callback', 'propietario', 'normal', 'high');
}

function travel_tour_propietario_data_callback($post)
{
    wp_nonce_field('travel_tour_save_propietario_data', 'travel_tour_propietario_data_meta_box_nonce');
    $user_id= get_post_meta($post->ID, 'user_id', true);
    $provincias = travel_tour_get_provincias_list();
    $name = get_user_meta($user_id, 'first_name', true);
    $lastname = get_user_meta($user_id, 'last_name', true);
    $carnet = get_user_meta($user_id, 'carnet', true);
    $address = get_user_meta($user_id, 'contact_address', true);
    $cp = get_user_meta($user_id, '_propietario_data_value_cp', true);
    $provincia = get_user_meta($user_id, 'travel_tour_propietario_data_provincia', true);
    $municipio = get_user_meta($user_id, 'travel_tour_propietario_data_municipio', true);
    $telefono = get_user_meta($user_id, '_propietario_data_value_telefono', true);
    $cell = get_user_meta($user_id, 'phone', true);
    $licencia = get_user_meta($user_id, 'licencia', true);
    $mail="";
    $nauta = get_user_meta($user_id, 'propietario_data_value_mail_nauta', true);
    $user_info = get_userdata($user_id);
    if($user_info){
        $mail=$user_info->user_email;
    }



//    $mail = get_user_meta($user_id, 'user_email', true);

    echo '<div id="propietario-data-value" class="data-form">';
    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_name"><span class="dashicons dashicons-businessman"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_name" name="first_name" value="' . esc_attr($name) . '" size="25" placeholder="Nombre" required/>';
    echo '</div>';
    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_lastname"><span class="dashicons dashicons-groups"></span> </label>';
    echo '<input type="text" id="propietario_data_value_lastname" name="last_name" value="' . esc_attr($lastname) . '" size="25" placeholder="Apellidos" required />';
    echo '</div>';

    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_carnet"><span class="dashicons dashicons-id"></span> </label>';
    echo '<input type="text" id="propietario_data_value_carnet" name="carnet" value="' . esc_attr($carnet) . '" size="25" maxlength="11" pattern="\d*" placeholder="CI" required />';
    echo '</div>';

    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_address"><span class="dashicons dashicons-admin-home"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_address" name="travel_tour_propietario_data_address" value="' . esc_attr($address) . '" size="25" placeholder="Dirección particular" required/>';
    echo '</div>';

    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_cp"><span class="dashicons dashicons-tag"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_cp" name="travel_tour_propietario_data_cp" value="' . esc_attr($cp) . '" size="25" placeholder="Código postal" required/>';
    echo '</div>';


    echo '<div class="propietario-data-value-input">';
    echo '<input type="hidden" id="provinicia-value" name="provinicia-value" value="' . esc_attr($provincia) . '" />';
    echo '<label for="travel_tour_propietario_data_provincia"> <span class="dashicons dashicons-location"></span> </label>';
    echo '<select id="travel_tour_propietario_data_provincia" name="travel_tour_propietario_data_provincia">';
    foreach ($provincias as $row) {
        if ($provincia === $row[0]) {
            echo ' <option value="' . esc_attr($row[0]) . '" selected="selected">' . $row[1] . '</option>';
        } else {
            echo ' <option value="' . esc_attr($row[0]) . '">' . $row[1] . '</option>';
        }

    }
    echo '</select>';
    echo '</div>';

    echo '<div class="propietario-data-value-input">';
    echo '<input type="hidden" id="municipio-value" name="municipio-value" value="' . esc_attr($municipio) . '" />';
    echo '<label for="travel_tour_propietario_data_municipio"><span class="dashicons dashicons-location-alt"></span> </label>';
    echo '<select id="travel_tour_propietario_data_municipio" name="travel_tour_propietario_data_municipio">';
    echo '</select>';
    echo '</div>';


    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_telf_fijo"><span class="dashicons dashicons-phone"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_telf_fijo" name="travel_tour_propietario_data_telf_fijo" value="' . esc_attr($telefono) . '" size="25" placeholder="Telefono fijo" />';
    echo '</div>';

    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_cell"><span class="dashicons dashicons-smartphone"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_cell" name="phone" value="' . esc_attr($cell) . '" size="25" 
    placeholder="Celular"/>';
    echo '</div>';
    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_licencia"><span class="dashicons dashicons-media-default"></span> </label>';
    echo '<input type="text" id="travel_tour_propietario_data_licencia" name="licencia" value="' . esc_attr($licencia) . '"  size="25"
    placeholder="Licencia" required/>';
    echo '</div>';
    if($user_info){
        echo '<div class="propietario-data-value-input">';
        echo '<label for="travel_tour_propietario_data_user"><span class="dashicons dashicons-admin-users"></span> </label>';
        echo '<input type="text" id="travel_tour_propietario_data_user" name="user_login_show" value="' . esc_attr($user_info->user_login) . '"  size="25" disabled/>';
        echo '<input type="hidden" id="travel_tour_propietario_data_login_user" name="user_login_hidden" value="' . esc_attr($user_info->user_login) . '"/>';
        echo '</div>';
        echo '<div class="propietario-data-value-input">';
        echo '<label for="travel_tour_propietario_data_mail"><span class="dashicons dashicons-email"></span> </label>';
        echo '<input type="email" id="travel_tour_propietario_data_mail" name="email" value="' . esc_attr($mail) . '"  size="25"
     disabled/> <input type="checkbox" id="checkBox" onclick="enableDisableMail(this.checked)"> Editar ';
        echo '<span id="message-mail"> </span>';
        echo '<input type="hidden" id="travel_tour_propietario_data_user_mail" name="user_mail_hidden" value="' . esc_attr($mail) . '"/>';
        echo '</div>';



    }else{
        echo '<div class="propietario-data-value-input">';
        echo '<label for="travel_tour_propietario_data_mail"><span class="dashicons dashicons-email"></span> </label>';
        echo '<input type="email" id="travel_tour_propietario_data_mail" name="email" value="' . esc_attr($mail) . '"  size="25"
    placeholder="E-mail" required/>';
        echo '<span id="message-mail"></span>';
        echo '</div>';
    }
    echo '<div class="propietario-data-value-input">';
    echo '<label for="travel_tour_propietario_data_mail_nauta"><span class="dashicons dashicons-email"></span> </label>';
    echo '<input type="email" id="travel_tour_propietario_data_mail_nauta" name="email_nauta" value="' . esc_attr($nauta) . '"  size="25"
    placeholder="Nauta e-mail" required/>';
    echo '</div>';
    if($user_info){
        echo '<div class="propietario-data-value-input">';
        echo '<label for="pass1-text"><span class="dashicons dashicons-lock"></span> </label>';
        echo '<button type="button" id="propietario-data-btn-value-pw-change" class="button wp-generate-pw hide-if-no-js" style="display: inline-block;">Generar nueva constrase&ntilde;a</button>';
        echo '<div class="wp-pwd hide-if-js" id="pass-data-block" style="display: none;">';
		echo	'<span class="password-input-wrapper">';
		echo    '<input type="password" name="propietario_data_value_password_change" id="propietario_data_value_password_change" class="regular-text" value="" autocomplete="off" aria-describedby="pass-strength-result" size="25">';
		echo    '</span>
			<button type="button" id="show_password_chang" class="button wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Hide password" onclick="changeVisibilityPswChange()">
				<span class="dashicons dashicons-hidden"></span>				
			</button>
			<button type="button" id="btn_cancel_chg_pw" class="button wp-cancel-pw hide-if-no-js" data-toggle="0" aria-label="Cancel password change">
				<span class="text">Cancel</span>
			</button>';
		echo '</div>';

        echo '</div>';
    }
    echo '<input type="hidden" id="secure-value" value="0" />';
    echo '</div>';

}

function travel_tour_propietario_data_profile_callback($post)
{
    wp_nonce_field('travel_tour_save_propietario_data_profile', 'travel_tour_propietario_data_profile_meta_box_nonce');

    $user = get_post_meta($post->ID, '_propietario_data_value_user', true);


    echo '<div id="propietario-data-value" class="data-form">';
    echo '<div class="propietario-data-value-input">';


    echo '<input type="radio" name="user" value="existe" checked> Usuario existente <input type="text" name="user-field-search" id="user-field-search" onkeyup="searchUser()"><br>';
    echo '<input type="radio" name="user" value="nuevo"> Nuevo usuario <br>';


    echo '</div>';
    echo '<hr>';
    echo '<div id="propietario-data-search">';

    echo '</div>';

    echo '</div>';

}

add_action('add_meta_boxes', 'travel_tour_propietario_add_meta_box');


add_filter('gettext', 'change_publish_button', 10, 2);

function change_publish_button($translation, $text)
{

    if ('propietario' == get_post_type() && ($text == 'Publish' || $text == 'Update')) {
        return 'Save';
    } else {
        return $translation;
    }
}


// function to be executed when a custom post type is published
//function run_when_post_published()
//{
//    // your function code here
//}

// replace {custom_post_type_name} with the name of your post type
//add_action( 'publish_propietario', 'run_when_post_published', 10, 2 );
//add_action('new_to_publish_propietario', 'run_when_post_published');
//add_action('draft_to_publish_propietario', 'run_when_post_published');
//add_action('pending_to_publish_propietario', 'run_when_post_published');

//Hide save draft button
function hide_publishing_actions()
{
    $my_post_type = 'propietario';
    global $post;
    if ($post->post_type == $my_post_type) {
        echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>
            ';
    }
}

add_action('admin_head-post.php', 'hide_publishing_actions');
add_action('admin_head-post-new.php', 'hide_publishing_actions');

/**
 * Save post metadata when a post is saved.
 *
 * @param int $post_id The post ID.
 * @param post $post The post object.
 * @param bool $update Whether this is an existing post being updated or not.
 */
function save_propietario_meta($post_id)
{

    /*
     * In production code, $slug should be set only once in the plugin,
     * preferably as a class property, rather than in each function that needs it.
     */
    $post_type = get_post_type($post_id);

    // If this isn't a 'book' post, don't update it.
    if ("propietario" != $post_type) return;
    $error = new WP_ERROR();

    if (!isset($_POST['user']) &&
        !isset($_POST['first_name']) &&
        !isset($_POST['last_name']) &&
        !isset($_POST['carnet']) &&
        !isset($_POST['travel_tour_propietario_data_address']) &&
        !isset($_POST['travel_tour_propietario_data_cp']) &&
        !isset($_POST['travel_tour_propietario_data_provincia']) &&
        !isset($_POST['travel_tour_propietario_data_municipio']) &&
        (!isset($_POST['travel_tour_propietario_data_telf_fijo']) || !isset($_POST['phone'])) &&
        !isset($_POST['licencia']) &&
        !isset($_POST['email'])&&
        !isset($_POST['email_nauta'])
    ) {

        $error->add('1', esc_html__('Please fill all required fields.', 'propietario'));
    } else {

        if (isset($_POST['user'])) {
            $user_id = "";
            $radioVal = $_POST["user"];
            $user_mail="";
            if(isset($_POST['user_mail_hidden'])){
                $user=get_user_by('login',$_POST['user_login_hidden']);;
                $user_id=$user->ID;
                $user_mail=$_POST['email'];
                global $wpdb;
                $tablename = $wpdb->prefix . "users";
                $wpdb->update( $tablename, array( 'user_email' => $user_mail ), array( 'ID' => $user_id ) );
                update_user_meta($user_id, 'user_email', sanitize_text_field($user_mail));
            }else if ($radioVal == "existe") {
                if (isset($_POST["userValue"])) {
                    $value = $_POST["userValue"];
                    $user_id = $value[0];
                    //$user_data=get_user_by('id',$user_id);
                    $user_data=get_userdata($user_id);
                    $user_mail=$user_data->user_email;
                    if (count($error)>0&&isset($_POST['email'])) {
                        if (!is_email($_POST['email'])) {
                            $error->add('2', esc_html__('Incorrect email address.', 'propietario'));
                        } else {
                            global $wpdb;
                            $tablename = $wpdb->prefix . "users";
                            $wpdb->update( $tablename, array( 'user_email' => $_POST['email'] ), array( 'ID' => $user_id ) );
                            update_user_meta($user_id, 'user_email', sanitize_text_field($_POST['email']));
                        }
                    }

                }elseif (isset($_POST['user_login_hidden'])){
                    $user = get_user_by('login',$_POST['user_login_hidden']);
                    $user_id=$user->ID;
                    update_user_meta($user_id, 'user_email', sanitize_text_field($_POST['user_mail_hidden']));
                }
                else {
                    $error->add('1', esc_html__('Please fill all required fields.', 'propietario'));
                    //error
                }
                //echo("You chose the first button. Good choice. :D");
            } else if ($radioVal == "nuevo") {
                // validate the data
                if (username_exists($_POST['username'])) {
                    $error->add('1', esc_html__('Username already exists, pleae try again with another name.', 'propietario'));

                } else {
                    $user_id = wp_insert_user(array(
                        'user_login' => $_POST['username'],
                        'user_pass' => $_POST['password'],
                        'user_email' => $_POST['email'],
                        'role' => 'subscriber'
                    ));
                    if (is_wp_error($user_id)) {
                        $error->add('1', esc_html__($user_id->get_error_message()), 'propietario');
                    //$error_messages = $user_id->get_error_message();

                    // successfully insert the user
                    }
                }
            }

            //save metadata
            if (count($error)>0) {
                if (isset($_POST['first_name'])) {
                    update_user_meta($user_id, 'first_name', sanitize_text_field($_POST['first_name']));
                }
                if (isset($_POST['last_name'])) {
                    update_user_meta($user_id, 'last_name', sanitize_text_field($_POST['last_name']));
                }
                if (isset($_POST['carnet'])) {
                    update_user_meta($user_id, 'carnet', sanitize_text_field($_POST['carnet']));
                }
                if (isset($_POST['travel_tour_propietario_data_address'])) {
                    update_user_meta($user_id, 'contact_address', sanitize_text_field($_POST['travel_tour_propietario_data_address']));
                }
                if (isset($_POST['travel_tour_propietario_data_cp'])) {
                    update_user_meta($user_id, '_propietario_data_value_cp', sanitize_text_field($_POST['travel_tour_propietario_data_cp']));
                }
                if (isset($_POST['travel_tour_propietario_data_provincia'])) {
                    update_user_meta($user_id, 'travel_tour_propietario_data_provincia', sanitize_text_field($_POST['travel_tour_propietario_data_provincia']));
                }
                if (isset($_POST['travel_tour_propietario_data_municipio'])) {
                    update_user_meta($user_id, 'travel_tour_propietario_data_municipio', sanitize_text_field($_POST['travel_tour_propietario_data_municipio']));
                }
                if (isset($_POST['travel_tour_propietario_data_telf_fijo'])
                    || isset($_POST['phone'])) {
                    update_user_meta($user_id, '_propietario_data_value_telefono', sanitize_text_field($_POST['travel_tour_propietario_data_telf_fijo']));
                    update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
                }
                if (isset($_POST['licencia'])) {
                    update_user_meta($user_id, 'licencia', sanitize_text_field($_POST['licencia']));
                }
                if (isset($_POST['email_nauta'])) {
                    update_user_meta($user_id, 'propietario_data_value_mail_nauta', sanitize_text_field($_POST['email_nauta']));
                }
                if(isset($_POST["propietario_data_value_password_change"])){
                    $password=$_POST["propietario_data_value_password_change"];
                    if($password!==''){
                        wp_set_password($password, $user_id);
                    }

                }
            }

            $error_message = $error->get_error_message();
            if (!empty($error_message)) {
                echo '<div class="tourmaster-notification-box tourmaster-failure" >' . $error_message . '</div>';;
            } else {
                update_post_meta($post_id, 'user_id', $user_id);
                global $wpdb;
                $wpdb->update( $wpdb->posts, array( 'post_title' =>  $_POST['first_name'].' '.$_POST['last_name'] ), array( 'ID' => $post_id ) );
                update_post_meta($post_id, 'title', $_POST['first_name'].' '.$_POST['last_name']);
                tourmaster_mail_notification('registration-complete-mail', null, $user_id);
            }


        }
    }


}

add_action('save_post', 'save_propietario_meta', 10);

add_filter( 'redirect_post_location', 'wpse_124132_redirect_post_location' );
/**
 * Redirect to the edit.php on post save or publish.
 */

function wpse_124132_redirect_post_location( $location ) {

    if ( 'propietario' == get_post_type() ) {

        /* Custom code for 'deals' post type. */

        if ( isset( $_POST['save'] ) || isset( $_POST['publish'] ) )
            return admin_url( "edit.php?post_type=propietario" );

    }
    return $location;
}