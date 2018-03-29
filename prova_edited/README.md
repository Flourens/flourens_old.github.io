# Installation

`npm install`

# Development

## Watch the code and compiles

`gulp watch`

## Gulp Task: include

To easily work on common parts like headers and footers, we use a really simple task to be able to include HTML files. The `src` folder contains those files. To compile and generate the final HTML files, just type `gulp include`. Those files will be put at the root folder, they shouldn't be modified and will be overriden at the next compilation.

## Gulp Task: styles

Simple compilation from SCSS to CSS with autoprefixer. Just type `gulp styles`

## Gulp Task: scripts

Take all the JS files, concat and minify. Result is *all.min.js*. Just type `gulp scripts`

# Going to production

Type `gulp` to generate and copiles all files (HTML includes, styles, scripts).

Take generated HTML files and `assets` folder on the root directory, upload them, and voila!