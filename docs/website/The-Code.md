Whether you want to help out or are just curious on how the website works, here's a bit more information on the code and the structure of the repository.

## Repository structure:
* `.git` Perhaps one of the most important folders of all. This folder contains all the information needed for GIT to store this code on GitHub and all of the history of the files.
* `api` This folder contains the code for the API, which is used to communicate with the database. More on the API can be found [here](https://github.com/ProDeoProductions/ProDeoWebsite/wiki/API).
* `css` This contains all the CSS files that are used for the different colors. In case you haven't noticed yet, there are 6 different colors used throughout the website. Each page is styled using one of the 6 colors.
* `img` All the images used on the website, which is currently just the logo and the background of the homepage.
* `locale` JSON and PHP files for localisation of the website. The plan is to someday use JSON files for this and have one JSON file per language for the website.
* `src` The actual source code! More information on the structure of this folder further below.
* `.htaccess` An important file that tells the server how to handle different web addresses that point to the website.
* `favicon` files. These are just used for the icon when you favorite the website or create a bookmark. It's also the little icon you see when opening a website on a tab in your browser.
* `index.php` The first page for every address on the website. Every bit of communication with the website will go through this page, except for the API. 

## Src directory structure:
* `modules` All web pages have been divided into "modules", parts to make it easier to repeat code and move it around. Every web page is created with the `Page` module and this module contains all the parts needed to build and fill web pages.
* `phpmailer` Code used to send emails.
* `scripts` Some extra calculations and code for specific pages.
* `template` Similar to the "modules" mentioned above, but used by each page for the header, navigation and footer.
* `tools` Some extra calculations and code for multiple/all pages.

## Used libraries and APIs
- [jQuery](https://api.jquery.com/)
- [jQuery Color](https://www.jsdelivr.com/package/npm/jquery-color)
- [Popper.js](popper.js.org)
- [Bootstrap](https://getbootstrap.com/docs/4.0/getting-started/introduction/)
- [Font Awesome](https://fontawesome.com/v4/icons/)
- [DataTables](https://datatables.net/)
- [Bootstrap Slider](https://seiyria.com/bootstrap-slider/)
- [Summernote](https://summernote.org/getting-started/)
- [SVG.js](https://svgjs.dev/docs/3.0/)
- [SVGPanZoom](https://github.com/bumbu/svg-pan-zoom)
- [SVGSaver](https://github.com/Hypercubed/svgsaver)
- [FileSaver](https://github.com/eligrey/FileSaver.js)
- [Canvas.toBlob](https://github.com/eligrey/canvas-toBlob.js)
- [Google Maps](https://developers.google.com/maps/documentation/javascript)
- [MarkerClusterer](https://googlemaps.github.io/js-markerclusterer/)

## More information
All the other necessary information should either be available via comments in the code itself or just by asking me.
