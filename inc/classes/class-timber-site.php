<?php

class JmSite extends TimberSite
{


  function __construct()
  {

    add_theme_support('post-thumbnails');
    add_theme_support('menus');
    add_theme_support('html5', array('search-form'));

    add_filter('timber_context', array($this, 'add_context_variables'));
    add_filter('get_twig', array($this, 'add_twig_helpers'));

    /**
     * Enqueue scripts and styles
     */
    add_action('wp_enqueue_scripts', array($this, 'enqueue_styles_and_scripts'));

    /**
     * Register menus
     */
    add_action('after_setup_theme', array($this, 'register_wp_menus'));

    add_action('after_setup_theme', array($this, 'editor_style'));

    add_action('init', array($this, 'register_location_taxonomies'));

    add_action('init', array($this, 'register_location_post_type'));

    /**
     * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
     */
    // add_action( 'vc_before_init', array($this, 'jm_vcSetAsTheme') );

    parent::__construct();
  }

  static public function parse_wpb_shortcode_textfield($str)
  {
    $replaced_str = str_replace(['``', '`{`', '`}`'], ['"', '[', ']'], $str);
    return $replaced_str;
  }

  function editor_style()
  {
    add_editor_style(trailingslashit(get_template_directory_uri()) . '/static/css/editor-style.css');
  }

  function register_wp_menus()
  {
    register_nav_menu('primary_menu', __('Primary Menu', THEME_TD));
    register_nav_menu('top_menu', __('Top Menu', THEME_TD));
    register_nav_menu('full_menu', __('Full Menu', THEME_TD));
    register_nav_menu('footer_menu',  __('Footer Menu', THEME_TD));
  }

  function enqueue_styles_and_scripts()
  {

    $theme = wp_get_theme();
    $theme_version = $theme->get('Version');

    global $post;
    $post_slug = "";

    if (isset($post->post_name)) {
      $post_slug = $post->post_name;
    }

    // CSS

    wp_enqueue_style(
      'bs',
      get_template_directory_uri() . '/css/bootstrap.min.css',
      array(),
      filemtime(get_template_directory() . '/css/bootstrap.min.css')
    );

    wp_enqueue_style(
      'main',
      get_template_directory_uri() . '/scss/main.css',
      ['bs'],
      filemtime(get_template_directory() . '/scss/main.css')
    );

    if (is_single()) {
      wp_enqueue_style(
        'editor-style',
        get_template_directory_uri() . '/css/editor-style.css',
        array(),
        filemtime(get_template_directory() . '/css/editor-style.css')
      );
    } else {
      wp_dequeue_style( 'wp-block-library' );
      wp_dequeue_style( 'wp-block-library-theme' );
    }

    // JS

    $load_at_the_bottom = true;

    wp_enqueue_script(
      'bootstrap.min.js',
      get_template_directory_uri() . '/static/js/bootstrap.min.js',
      array('jquery'),
      filemtime(get_template_directory() . '/static/js/bootstrap.min.js'),
      $load_at_the_bottom
    );

    wp_enqueue_script(
      'script.js',
      get_template_directory_uri() . '/static/js/script.js',
      array('jquery'),
      filemtime(get_template_directory() . '/static/js/script.js'),
      $load_at_the_bottom
    );
  }

  function jm_vcSetAsTheme()
  {
    vc_set_as_theme();
  }

  function get_theme_menu_name($theme_location)
  {
    $theme_locations = get_nav_menu_locations();

    $menu_obj = get_term($theme_locations[$theme_location], 'nav_menu');

    return $menu_obj->name;
  }

  function add_context_variables($context)
  {

    // Add theme version variable
    $theme = wp_get_theme();
    $theme_version = $theme->get('Version');
    $context['theme_version'] = $theme_version;

    // PAGES
    $context['blog_url'] = get_post_type_archive_link('post');
    $context['locations_url'] = get_post_type_archive_link('location');
    $context['home_url'] = site_url('/');
    $context['environment_url'] = site_url('/environment/');
    $context['parent_info_url'] = site_url('/parent-info/');
    $context['virtual_tours_url'] = site_url('/virtual-tours/');

    $context['data_url'] = get_template_directory_uri() . '/static/data';
    $context['img_url'] = get_template_directory_uri() . '/static/img';

    $context['loc_categories'] = array_reverse(Timber::get_terms('loc-category'));

    $context['categories'] = Timber::get_terms('category');
    $context['selected_category'] = get_query_var('cat');

    if (has_nav_menu('primary_menu')) {
      $context['primary_menu'] = new TimberMenu('primary_menu');
    }

    if (has_nav_menu('top_menu')) {
      $context['top_menu'] = new TimberMenu('top_menu');
    }

    if (has_nav_menu('full_menu')) {
      $context['full_menu'] = new TimberMenu('full_menu');
    }

    if (has_nav_menu('footer_menu')) {
      $context['footer_menu'] = new TimberMenu('footer_menu');
    }
    $context['site'] = $this;
    return $context;
  }

  function add_twig_helpers($twig) {
    /* this is where you can add your own functions to twig */

    $function = new Twig_SimpleFunction('menu_name', function ($theme_location) {

      return $this->get_theme_menu_name($theme_location);
    });
    $twig->addFunction($function);

    $function = new Twig_SimpleFunction('format_website_url', function ($url) {

      $url = preg_replace('#^https?://#', '', rtrim($url, '/'));
      return $url;
    });
    $twig->addFunction($function);


    return $twig;
  }

  function register_location_taxonomies() {
    register_taxonomy(
      'loc-category',
      'location',
      array(
        'label' => __('Category'),
        'rewrite' => array('slug' => 'loc-category'),
        'hierarchical' => true,
      )
    );
  }

  // Register Custom Post Type
  function register_location_post_type()
  {
    $labels = array(
      'name'                  => _x('Locations', 'Post Type General Name', THEME_TD),
      'singular_name'         => _x('Location', 'Post Type Singular Name', THEME_TD),
      'menu_name'             => __('Locations', THEME_TD),
      'name_admin_bar'        => __('Location', THEME_TD),
      'archives'              => __('Item Archives', THEME_TD),
      'attributes'            => __('Item Attributes', THEME_TD),
      'parent_item_colon'     => __('Parent Item:', THEME_TD),
      'all_items'             => __('All Items', THEME_TD),
      'add_new_item'          => __('Add New Item', THEME_TD),
      'add_new'               => __('Add New', THEME_TD),
      'new_item'              => __('New Item', THEME_TD),
      'edit_item'             => __('Edit Item', THEME_TD),
      'update_item'           => __('Update Item', THEME_TD),
      'view_item'             => __('View Item', THEME_TD),
      'view_items'            => __('View Items', THEME_TD),
      'search_items'          => __('Search Item', THEME_TD),
      'not_found'             => __('Not found', THEME_TD),
      'not_found_in_trash'    => __('Not found in Trash', THEME_TD),
      'featured_image'        => __('Featured Image', THEME_TD),
      'set_featured_image'    => __('Set featured image', THEME_TD),
      'remove_featured_image' => __('Remove featured image', THEME_TD),
      'use_featured_image'    => __('Use as featured image', THEME_TD),
      'insert_into_item'      => __('Insert into item', THEME_TD),
      'uploaded_to_this_item' => __('Uploaded to this item', THEME_TD),
      'items_list'            => __('Items list', THEME_TD),
      'items_list_navigation' => __('Items list navigation', THEME_TD),
      'filter_items_list'     => __('Filter items list', THEME_TD),
    );
    $rewrite = array(
      'slug'                  => 'location',
      'with_front'            => true,
      'pages'                 => true,
      'feeds'                 => false,
    );
    $args = array(
      'label'                 => __('Location', THEME_TD),
      'description'           => __('This is Location pages', THEME_TD),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'revisions', 'thumbnail'),
      'taxonomies'            => array('loc-category'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-location',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => true,
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      // 'rewrite'               => false,
      'rewrite'               => $rewrite,
      'capability_type'       => 'page',
    );
    register_post_type('location', $args);
  }

}

new JmSite();
