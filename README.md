# General

This is a WordPress theme that is based on the Twig template engine. It means that you need to have [timber](https://wordpress.org/plugins/timber-library/) plugin installed and activated too.

The easiest way to start work is - to clone the existing staging website: [redwood-full.jmarketing.agency](https://redwood-full.jmarketing.agency/)

## Styles

In this theme, I'm using SCSS. All components are located inside "/scss" directory. It compiles all the assets into a single /scss/main.css file which WordPress uses.

I'm using this [extension](https://marketplace.visualstudio.com/items?itemName=glenn2223.live-sass) for VS Code to compile "/scss" into "main.css", really recommend that one.

## Color Scheme

Since we build almost same theme for all 5 brands, then it makes sense to use variables for colors.

In "base.twig" you can find all the variables:

```css
:root {
	--base-font-name: 'Poppins', sans-serif;
	--secondary-font-name: 'Kameron';

	--primary-menu-color: #FFF2DA;
	--primary-menu-label-color: #000;
	--primary-menu-hover-label-color: #C41230;
	--primary-menu-item-color: transparent;
	--primary-menu-hover-item-color: #e1e1e1;

  and more...
}
```

## Fonts

We use Google Fonts only. Ask the designer if you need some help finding the corresponding font.

## Directories

/css - contains vendor .css files

/inc/classes/class-timber-site.php - all the main configuration for theme. Route helpers, menus, enqueue css, js and register custom post type

/inc/classes/class-customizer.php - settings for WP Customize

/static - images and js

/templates - all Twig templates, most of the work will be in that directory
