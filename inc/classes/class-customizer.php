<?php

/**
 * Class JmCustomizer
 * For WordPress Customizer management
 */
class JmCustomizer
{

  const STRING_SETTINGS = array(
    'phone_1' => 'Phone Number',
    'whatsapp' => 'WhatsApp',
    'contact_email' => 'Contact Email',
    'address' => 'Address',
  );

  const SOCIALS = array(
    'facebook' => 'Facebook',
    'youtube' => 'Youtube',
    'instagram' => 'Instagram',
    // 'linkedin' => 'LinkedIn',
  );

  const USER_FORMS = array(
    // 'contact_form' => 'Contact Us Form',
    'footer_form' => 'Footer Form',
    // 'signup_form' => 'Sign Up Form',
  );

  function __construct()
  {

    add_action('customize_register', array($this, 'customize_register'));
  }

  function customize_register($wp_customize)
  {
    //All our sections, settings, and controls will be added here

    $wp_customize->add_section('general_settings', array(
      'title'      => __('General', THEME_TD),
      'priority'   => 30,
    ));

    // Socials settings
    foreach (self::SOCIALS as $social_id => $label) {


      $wp_customize->add_setting($social_id, array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'esc_url_raw',
      ));

      $wp_customize->add_control($social_id, array(
        'type'     => 'url',
        'label'      => __($label, THEME_TD),
        'section'    => 'general_settings',
        'priority' => 5
      ));
    }

    // Forms settings

    foreach (self::USER_FORMS as $form_id => $label) {


      $wp_customize->add_setting($form_id, array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
        //                'sanitize_callback' => 'esc_attr',
      ));

      $wp_customize->add_control($form_id, array(
        'type'     => 'text',
        'label'      => __($label, THEME_TD),
        'section'    => 'general_settings',
        'priority' => 5
      ));
    }

    // String Settings

    foreach (self::STRING_SETTINGS as $setting_id => $label) {


      $wp_customize->add_setting($setting_id, array(
        'type' => 'theme_mod',
        'capability' => 'edit_theme_options',
      ));

      $wp_customize->add_control($setting_id, array(
        'type'     => 'text',
        'label'      => __($label, THEME_TD),
        'section'    => 'general_settings',
        'priority' => 5
      ));
    }

    // Custom HTML

    $wp_customize->add_setting('custom_html', array(
      'type' => 'theme_mod',
      'capability' => 'edit_theme_options',
    ));

    $wp_customize->add_control('custom_html', array(
      'type'     => 'textarea',
      'label'      => __('Custom HTML', THEME_TD),
      'section'    => 'general_settings',
      'priority' => 5
    ));
  }
}

new JmCustomizer();
