# 🍇 LaraGrape - Laravel + GrapesJS + Filament Boilerplate

A powerful and modern web development boilerplate that combines:
- **Laravel** - Robust PHP framework
- **GrapesJS** - Visual website builder 
- **Filament** - Modern admin panel

## ✨ Features

- 🎨 **Visual Page Builder** - Drag & drop website building with GrapesJS
- 📱 **Responsive Design** - Built with Tailwind CSS
- 🔧 **Admin Panel** - Beautiful Filament admin interface
- 📄 **Page Management** - Create, edit, and manage pages visually
- 🔍 **SEO Optimized** - Built-in meta tags and SEO features
- 🚀 **Production Ready** - Optimized for performance

## 🚀 Quick Start

### Prerequisites

- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite/MySQL/PostgreSQL

### Installation

1. **Install PHP dependencies**
```bash
composer install
```

2. **Install Node dependencies**
```bash
npm install
```

3. **Set up environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Configure database**
Edit `.env` file with your database credentials or use SQLite (default).

5. **Run migrations**
```bash
php artisan migrate
```

6. **Create admin user**
```bash
php artisan make:filament-user
```

7. **Build assets**
```bash
npm run build
```

8. **Start development server**
```bash
php artisan serve
```

Visit `http://localhost:8000` to see your site!
Admin panel: `http://localhost:8000/admin`

## 📖 Usage

### Creating Pages

1. Go to the admin panel (`/admin`)
2. Navigate to "Pages"
3. Click "Create Page"
4. Fill in basic information
5. Use the "Visual Editor" tab to design your page with GrapesJS
6. Publish when ready!

### Visual Editor Features

The GrapesJS editor includes:

- **Pre-built Components**:
  - Hero sections
  - Text blocks
  - Images & videos
  - Buttons & forms
  - Cards & columns
  - And more!

- **Responsive Design**: Preview and edit for desktop, tablet, and mobile
- **Style Manager**: Customize colors, fonts, spacing, and more
- **Layer Management**: Organize your page elements

### Frontend Features

- Responsive navigation with mobile menu
- SEO-optimized page rendering
- Clean, modern design with Tailwind CSS
- Automatic sitemap generation (coming soon)

## 🏗️ Architecture

### Models

- **Page**: Stores page content, metadata, and GrapesJS data

### Controllers

- **PageController**: Handles frontend page display
- **PageResource**: Filament admin resource for page management

### Views

- **components/layout/app.blade.php**: Main application layout
- **components/layout/header.blade.php**: Site header with navigation
- **components/layout/footer.blade.php**: Site footer
- **components/layout/grapejs-edit-bar.blade.php**: GrapesJS edit controls
- **pages/show.blade.php**: Frontend page template (simplified)
- **filament/forms/components/grapesjs-editor.blade.php**: GrapesJS editor component

### Assets

- **app.js**: Alpine.js components and site functionality
- **site.css**: Site-wide styles and component styling
- **Tailwind CSS**: For styling and responsive design

### Services

- **BlockService**: Dynamic block loading and management

## 🛠️ Customization

### Dynamic Block System

LaraGrape includes a powerful dynamic block system that automatically loads blocks from `resources/views/filament/blocks/`. Blocks are organized by category:

```
resources/views/filament/blocks/
├── layouts/          # Layout blocks (hero, section, columns)
├── content/          # Content blocks (text, heading)
├── media/            # Media blocks (image, video)
├── forms/            # Form blocks (contact form, newsletter)
└── components/       # Component blocks (card, button)
```

#### Creating New Blocks

1. **Choose a category** and create a `.blade.php` file
2. **Add metadata** at the top of the file:

```blade
{{-- @block id="my-block" label="My Block" description="A description of my block" --}}
<div class="my-block">
    <!-- Your HTML content here -->
</div>
```

3. **Make elements editable** with GrapesJS attributes:

```blade
<h3 data-gjs-type="text" data-gjs-name="title">Editable Title</h3>
```

See `BLOCKS_README.md` for detailed documentation.

### Adding Custom Blocks (Legacy Method)

Edit `resources/js/grapesjs-editor.js` and add new blocks to the `blockManager.blocks` array:

```javascript
{
    id: 'custom-block',
    label: 'Custom Block',
    content: '<div class="custom-block">Your HTML here</div>'
}
```

### Styling

- Global styles: `resources/views/pages/show.blade.php`
- Admin styles: Use Filament's theming system
- Custom CSS: Add to the page template or via GrapesJS

### Templates

Add new page templates by:
1. Adding options to the PageResource template select
2. Creating corresponding view files
3. Updating the PageController logic

## 📁 Directory Structure

```
app/
├── Filament/
│   ├── Forms/Components/     # Custom Filament form components
│   └── Resources/            # Filament admin resources
├── Http/Controllers/         # Web controllers
├── Models/                   # Eloquent models
└── Services/                 # Application services

resources/
├── js/                       # JavaScript files
│   └── app.js               # Alpine.js components
├── css/                      # Stylesheets
│   ├── app.css              # Main CSS with imports
│   └── site.css             # Site-specific styles
└── views/
    ├── components/           # Reusable components
    │   ├── layout/          # Layout components
    │   │   ├── app.blade.php
    │   │   ├── header.blade.php
    │   │   ├── footer.blade.php
    │   │   └── grapejs-edit-bar.blade.php
    │   ├── blocks/          # Block components (future)
    │   └── forms/           # Form components (future)
    ├── filament/
    │   ├── blocks/          # Dynamic block system
    │   │   ├── layouts/     # Layout blocks
    │   │   ├── content/     # Content blocks
    │   │   ├── media/       # Media blocks
    │   │   ├── forms/       # Form blocks
    │   │   └── components/  # Component blocks
    │   └── forms/components/ # Filament form components
    └── pages/               # Frontend page templates

database/
└── migrations/              # Database migrations
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is open-sourced software licensed under the [MIT license](LICENSE).

## 🔧 Troubleshooting

### Common Issues

**GrapesJS not loading:**
- Ensure `npm run build` was executed
- Check browser console for JavaScript errors

**Pages not displaying:**
- Verify routes are correctly set up
- Check that pages are marked as published

**Admin panel not accessible:**
- Run `php artisan make:filament-user` to create an admin user
- Clear cache: `php artisan optimize:clear`

### Support

For issues and questions:
1. Check the documentation
2. Search existing issues
3. Create a new issue with detailed information

## 🚀 Deployment

### Production Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Configure database
- [ ] Run `php artisan optimize`
- [ ] Run `npm run build`
- [ ] Set up proper file permissions
- [ ] Configure web server
- [ ] Set up SSL certificate

---

Built with ❤️ using Laravel, GrapesJS, and Filament.
Madeby Streats22 // StreatsDesign
