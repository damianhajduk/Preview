/*
Ukoly nad assety
================

1) Kopirovani souboru
2) CSS: LESS, PostCSS
3) JS: minifikace
4) Workflow: BrowserSync, watch
5) Alias tasky: css, js, default

TODO:
- v Chrome nefunguji source maps po zapojeni BrowserSync
  (Firefox je v pohode v obou situacich)
  https://github.com/BrowserSync/browser-sync/issues/639

*/

module.exports = function(grunt) {
  "use strict";

  // zjistujeme cas behu tasku
  require('time-grunt')(grunt);

  // jit-grunt pro zrychleni nacitani gruntu a behu tasku
 require('jit-grunt')(grunt);


  // Nastaveni tasku
  grunt.initConfig({

    pkg: grunt.file.readJSON('package.json'),

    // 1) Kopirovani souboru
    // ---------------------

    /*copy: {
      jquery: {
        files: [
          {
            expand: true,
            cwd: 'bower_components/jquery/dist/',
            src: ['jquery.js'],
            dest: 'js/'
          }
        ]
      }
    },*/

    // CSS
    // ---

    // SASS -> CSS
	sass: {
        options: {
            sourceMap: true
        },
        dist: {
            files: {
                './css/main.css': './sources/sass/main.scss'
            }
        }
    },
   
   // PostCSS

    postcss: {
      options: {
        map: true,
        processors: [
          require('pixrem')({rootValue: 16}), // rem -> px fallback
          require('autoprefixer')({browsers: 'last 2 versions'}), // pridani prefixu
        ]
      },
      dist: {
        src: './css/main.css'
      }
    },

	cssmin: {
      css: {
        files: {
          './css/main.min.css':
          './css/main.css'
        }
      }
    },

    // Javascript
    // ----------

    // Uglify: minifikace JS

    uglify: {
      default: {
        files: {
            /*'src/js/script.min.js': [
              'static/js/ios-orientationchange-fix.js',                
              'static/lightslider/js/lightslider.js',
              'static/js/jquery-validation/jquery.validate.js',
              'static/js/jquery-validation/additional-methods.js',
              'static/js/jquery-validation/messages_cs.js',
              'static/js/typeahead.min.js',
              'static/js/jquery-ui.js'
            ],*/
            './js/main.min.js':['./sources/js/main.js']
          /*'static/js/storelocator.min.js': [
            'static/js/storelocator/infobubble-compiled.js',
            'static/js/storelocator/store-locator.min.js',
            'static/js/storelocator/medicare-static-ds.js',
            'static/js/storelocator/panel.js',
            'static/js/storelocator/cluster.js',
            'static/js/storelocator/custom.js'
          ]*/
        }
      }
    },

    // watch
    // -----

    // Sleduje zmeny v LESS a JS souborech a spousti souvisejici tasky.

    watch: {
      sass: {
        files: './sources/sass/**/*.scss',
        tasks: ['css']
      },
      js: {
        files: './sources/js/main.js',
        tasks: ['js']
      }
    },

       
    imagemin: {
      png: {
        options: {
          optimizationLevel: 7
        },
        files: [
          {
            expand: true,
            cwd: 'static/', // cwd is 'current working directory'
            src: ['**/*.png'],
            dest: 'static-compressed/', // Could also match cwd.
            ext: '.png'
          }
        ]
      },
      jpg: {
        options: {
          progressive: true
        },
        files: [
          {
            expand: true, // Tell Grunt where to find our images and where to export them to.
            cwd: 'photo-products/', // cwd is 'current working directory'
            src: ['**/*.jpg'],
            dest: 'photo-products-compressed/', // Could also match cwd.
            ext: '.jpg'
          }
        ]
      }
    }
  });
  


  // 5) Alias tasky
  // --------------

  grunt.registerTask('css', ['sass', 'postcss','cssmin']);
  grunt.registerTask('js', ['uglify']);
  //grunt.registerTask('default', ['copy', 'css', 'js', 'browserSync', 'watch']);
  grunt.registerTask('obrazky', ['imagemin']);
  grunt.registerTask('default', ['css', 'js', 'watch']);
  
};