# My Super Tour Elementor Widgets

Custom Elementor widgets for My Super Tour website featuring liquid glass design, warm purple/yellow color palette, and advanced animations.

## Features

- **Liquid Glass Design**: All widgets feature semi-transparent backgrounds with backdrop blur effects
- **Warm Color Palette**: Purple (HSL 270, 70%, 60%) and Yellow (HSL 45, 98%, 60%)
- **Animations**: Hover effects, parallax scrolling, smooth transitions
- **Fully Customizable**: All widgets have Elementor visual controls
- **Responsive**: Mobile-friendly designs

## Installation

1. Upload the `my-super-tour-elementor` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Open Elementor editor and find widgets in "My Super Tour" category

## Requirements

- WordPress 5.0 or higher
- Elementor 3.0 or higher
- PHP 7.4 or higher

## Included Widgets

### 1. Liquid Glass Card
Generic card with glass morphism effect, icon, title, and description. Perfect for features, benefits, or information blocks.

**Controls:**
- Icon selection
- Title and description text
- Color variant (default, purple, yellow)

### 2. Floating Glass Orbs
Animated background orbs with parallax scrolling effect. Creates atmospheric depth.

**Controls:**
- Number of orbs (1-10)
- Enable/disable parallax

### 3. Grainy Hero Section
Hero banner with background image, grain texture overlay, gradient, title, subtitle, and CTA button.

**Controls:**
- Background image
- Title and subtitle
- Button text and link

### 4. Tour Card
Interactive card for displaying tours with image, location, price, rating, and booking button.

**Controls:**
- Tour image
- Title, location, price
- Rating and review count
- Link URL

### 5. Review Card
Customer review display with avatar, name, city, product name, rating, and review text.

**Controls:**
- Guest photo
- Name, city, date
- Product name
- Rating (1-5 stars)
- Review text

### 6. Trust Badge
Small badge displaying trust indicators with icon and text.

**Controls:**
- Icon selection
- Title and description

### 7. Category Card
Large clickable card with image overlay and title for service categories.

**Controls:**
- Category image
- Title
- Link URL

### 8. Carousel
Horizontal scrolling carousel with navigation arrows, displaying multiple items.

**Controls:**
- Repeater for items (image, title, subtitle)
- Automatic responsive behavior

### 9. Team Section
Grid layout for team member profiles with photos, names, roles, and descriptions.

**Controls:**
- Repeater for team members
- Photo, name, role, description per member

### 10. Contact & Instagram
Two-column section with contact information and Instagram promotion with phone mockup.

**Controls:**
- Contact title, email, phone
- Instagram title, handle
- Phone mockup image

## Styling

All styles are defined in `assets/css/widgets.css` using CSS custom properties for easy customization:

```css
:root {
  --mst-purple: hsl(270, 70%, 60%);
  --mst-yellow: hsl(45, 98%, 60%);
  --mst-glass-border: hsla(0, 0%, 100%, 0.2);
  --mst-glass-bg: hsla(0, 0%, 100%, 0.1);
}
```

## JavaScript

Interactive functionality is handled in `assets/js/widgets.js`:

- Parallax scrolling for floating orbs
- Carousel navigation and touch support
- Scroll reveal animations
- Button handlers

## Support

For support, please contact the My Super Tour development team.

## Version

1.0.0

## License

Proprietary - My Super Tour
