let mix = require("laravel-mix");

mix
  .js("resources/assets/js/app.js", "public/js")
  .vue()
  .sass("resources/assets/sass/app.scss", "public/css");

// Optional: Add versioning for production builds
if (mix.inProduction()) {
  mix.version();
}
