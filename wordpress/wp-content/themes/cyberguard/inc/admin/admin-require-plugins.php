<?php
/**
 * Include the TGM_Plugin_Activation class.
 */
get_template_part( 'inc/admin/libs/tgmpa/class-tgm-plugin-activation' );

add_action( 'tgmpa_register', 'cyberguard_register_required_plugins' );
function cyberguard_register_required_plugins() {
    include( locate_template( 'inc/admin/demo-data/demo-config.php' ) );
    $pxl_server_info = apply_filters( 'pxl_server_info', ['plugin_url' => 'https://api.bravisthemes.net/plugins/'] ) ; 
    $default_path = $pxl_server_info['plugin_url'];  
    $images = get_template_directory_uri() . '/inc/admin/assets/img/plugins';
    $plugins = array(

        array(
            'name'               => esc_html__('Redux Framework', 'cyberguard'),
            'slug'               => 'redux-framework',
            'required'           => true,
            'logo'        => $images . '/redux.png',
            'description' => esc_html__( 'Build theme options and post, page options for WordPress Theme.', 'cyberguard' ),
        ),

        array(
            'name'        => esc_html__('Classic Editor', 'cyberguard'),
            'slug'        => 'classic-editor',
            'required'    => true,
            'logo'        => $images . '/classic-editor.png',
            'description' => esc_html__('Classic Editor restores the previous WordPress editor and the Edit Post screen.', 'cyberguard'),
        ),

        array(
            'name'               => esc_html__('Elementor', 'cyberguard'),
            'slug'               => 'elementor',
            'required'           => true,
            'logo'        => $images . '/elementor.png',
            'description' => esc_html__( 'Introducing a WordPress website builder, with no limits of design. A website builder that delivers high-end page designs and advanced capabilities', 'cyberguard' ),
        ),

        array(
            'name'               => esc_html__('Mailchimp', 'cyberguard'),
            'slug'               => "mailchimp-for-wp",
            'required'           => true,
            'logo'        => $images . '/mailchimp.png',
            'description' => esc_html__( 'Allowing your visitors to subscribe to your newsletter should be easy. With this plugin, it finally is.', 'cyberguard' ),
        ),

        array(
            'name'        => esc_html__('One User Avatar', 'cyberguard'),
            'slug'        => 'one-user-avatar',
            'required'    => true,
            'logo'        => $images . '/one-user-avatar.png',
            'description' => esc_html__('Allows users to upload custom avatars directly from their profile page.', 'cyberguard'),
        ),

        array(
            'name'               => esc_html__('Bravis Addons', 'cyberguard'),
            'slug'               => 'bravis-addons',
            'source'             => 'bravis-addons.zip',
            'required'           => true,
            'logo'        => $images . '/bravis-addons.png',
            'description' => esc_html__( 'Main process and Powerful Elements Plugin, exclusively for cyberguard WordPress Theme.', 'cyberguard' ),
        ),
  
        array(
            'name'               => esc_html__('Contact Form 7', 'cyberguard'),
            'slug'               => 'contact-form-7',
            'required'           => true,
            'logo'        => $images . '/contact-f7.png',
            'description' => esc_html__( 'Contact Form 7 can manage multiple contact forms, you can customize the form and the mail contents flexibly with simple markup', 'cyberguard' ),
        ),
    );
 

    $config = array(
        'default_path' => $default_path,           // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'is_automatic' => true,
    );

    tgmpa( $plugins, $config );

}