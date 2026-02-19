# WP Text Styler

Production-ready WordPress plugin that adds **highlight colors** and **styled boxes** to the Classic Editor (TinyMCE) using CSS classes — no inline styles.

---

## Features

- 🎨 4 default highlight colors (Yellow, Green, Blue, Red)
- 📦 4 box presets (Info, Warning, Success, Custom)
- 🎛️ Full settings page under **Settings › Text Styler**
- ✅ Enables per post type (including Custom Post Types)
- 🔄 Changing a color in settings instantly updates all existing content
- 🔒 WordPress Security Standards: nonces, sanitization, escaping
- 🌍 Translation-ready (POT file included)
- ⚙️ Compatible with WordPress 6.x

---

## File Structure

```
wp-text-styler/
├── wp-text-styler.php          # Main plugin file (headers + bootstrap)
├── includes/
│   ├── class-defaults.php      # Default highlight & box values
│   ├── class-assets.php        # Script/style registration
│   ├── class-tinymce.php       # TinyMCE hooks + AJAX CSS endpoint
│   └── class-css-generator.php # Builds & caches dynamic CSS
├── admin/
│   └── class-settings-page.php # Settings page (Settings API)
├── assets/
│   ├── css/
│   │   ├── editor.css          # Base styles loaded inside TinyMCE iframe
│   │   └── frontend.css        # Base frontend styles
│   └── js/
│       ├── tinymce-plugin.js   # TinyMCE plugin (dropdown + format logic)
│       └── settings.js         # Color picker + live preview on settings page
└── languages/
    ├── wp-text-styler.pot      # Translation template
    └── tinymce-plugin.php      # TinyMCE i18n registration
```

---

## Installation

1. Copy the `wp-text-styler` folder into `wp-content/plugins/`.
2. Activate the plugin in **Plugins › Installed Plugins**.
3. Go to **Settings › Text Styler** to configure colors, boxes, and which post types show the toolbar button.
4. Open any post/page (Classic Editor must be active), and you'll see a **"Text Styles"** dropdown in the toolbar.

---

## Requirements

- WordPress 6.0 or higher
- PHP 7.4 or higher
- Classic Editor plugin (or the block editor must be disabled)

---

## How It Works

### Highlights
Select text → click **Text Styles › Highlight › Yellow** → the text is wrapped in:
```html
<span class="plugin-highlight-yellow">your text</span>
```

The CSS class is defined dynamically. When you change the color in settings, the `.plugin-highlight-yellow` rule is regenerated and all previously highlighted content updates automatically.

### Boxes
Select text → click **Text Styles › Box › Info Box** → the text is wrapped in:
```html
<div class="plugin-box-info">your text</div>
```

### Remove Style
Select styled text → click **Text Styles › Remove Style** to strip all plugin classes.

---

## CSS Class Reference

| Class | Description |
|---|---|
| `plugin-highlight-yellow` | Yellow highlight |
| `plugin-highlight-green` | Green highlight |
| `plugin-highlight-blue` | Blue highlight |
| `plugin-highlight-red` | Red highlight |
| `plugin-box-info` | Info box |
| `plugin-box-warning` | Warning box |
| `plugin-box-success` | Success box |
| `plugin-box-custom` | Custom box |

---

## Adding New Post Types

Go to **Settings › Text Styler** and check any public post type under "Enable for Post Types".

---

## Adding More Colors or Boxes

Currently the plugin ships with 4 highlights and 4 boxes. To add more:
1. Go to **Settings › Text Styler** — the settings page dynamically renders all saved items.
2. In a future version, "Add Row" buttons will allow adding items without touching code.

For now, you can add items programmatically via the `wp_text_styler_highlights` and `wp_text_styler_boxes` options (arrays following the same structure as the defaults in `class-defaults.php`).

---

## Translating

Copy `languages/wp-text-styler.pot` to `languages/wp-text-styler-{locale}.po`, translate the strings, and compile to `.mo`. WordPress will load it automatically.

---

## License

GPL v2 or later.
