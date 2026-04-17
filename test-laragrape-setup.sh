#!/bin/bash
# Run from StreatsDesign directory after: composer create-project laravel/laravel test-laragrape
set -e
cd "$(dirname "$0")/test-laragrape" || { echo "Run from StreatsDesign. Create test-laragrape first: composer create-project laravel/laravel test-laragrape"; exit 1; }

echo "Adding LaraGrape path repository..."
composer config repositories.laragrape '{"type":"path","url":"../packages/streats22/LaraGrape","options":{"symlink":true}}'

echo "Requiring LaraGrape and Filament..."
composer require streats22/laragrape:@dev filament/filament:^5.0 --no-interaction

echo "Installing Filament panel..."
php artisan filament:install --panels --no-interaction

echo "Running LaraGrape setup..."
php artisan laragrape:setup --all --force

echo "Building assets..."
npm install && npm run build

echo "Creating admin user (admin@test.local / password)..."
php artisan tinker --execute="
\$u = \App\Models\User::firstOrCreate(
  ['email' => 'admin@test.local'],
  ['name' => 'Test Admin', 'password' => 'password']
);
\$u->password = 'password';
\$u->save();
echo 'Done';
"

echo ""
echo "Setup complete! Visit http://test-laragrape.test/admin (or php artisan serve)"
echo "Login: admin@test.local / password"
