##############
Rejstriky.info
##############
A portal for displaying information from open registers.

##################
Technologies used:
##################
- PHP 7.0+
- MariaDB 10+
- CodeIgniter 3.1.9

#############
Minification:
#############
We use:
npm install uglify-js -g
npm install uglifycss -g

JS
- \js           uglifyjs --compress --mangle --output main.min.js libs\jquery-3.3.1.min.js libs\nouislider.min.js libs\slick.js main.js libs\jquery-ui.min.js datepicker.js search_results.js isir_detail.js
- \js           uglifyjs --compress --mangle --output relations.min.js libs\jit-yc.js relations_page.js
- \js           uglifyjs --compress --mangle --output ie.min.js ie.js

CSS
- \css          uglifycss --output styles.min.css jquery-ui.min.css styles.css styles_ex.css relations.css
- \css          uglifycss --output cms.min.css cms.css
- \css          uglifycss --output print.min.css print.css
- \css          uglifycss --output ie.min.css ie.css
