let mix = require("laravel-mix");

// Disable OS notifications
mix.disableNotifications();

mix
  .js("resources/assets/js/app.js", "public/js")
  .vue()
  .sass("resources/assets/sass/app.scss", "public/css");

// Add versioning for production builds
if (mix.inProduction()) {
  mix.version();
}
