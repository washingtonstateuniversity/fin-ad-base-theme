module.exports = function(grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),

        /*concat: {
            options: {
                sourceMap: true
            },
            dist: {
                src: 'css/*.css',
                dest: 'tmp-style.css'
            }
        },*/
        watch: {
            files: [
                "css/**/*",
                "builder-templates/**/*",
                "inc/**/*",
                "**/**/*.php"
            ],
            tasks: [/*"concat",*/ "sass", "postcss", "cssmin", "copy", "csslint", "clean", "phpcbf", "phpcs"]
        },
		sass: {
            options: {
                sourceMap: true
            },
            style: {
                files: [
                    { src: "css/scss/style.scss", dest: "build/_post_sass/style.css" },
                ]
            },
        },


        postcss: {
            options: {
                map: false,//true,
                diff: false,
                processors: [
                    require( "autoprefixer" )( {
                        browsers: [ "> 1%", "ie 8-11", "Firefox ESR" ]
                    } )
                ]
            },
            dist: {
                src: "build/_post_sass/style.css",
                dest: "build/_precss/style.css"
            }
        },


        cssmin: {
            options: {
                sourceMap: true,
            },
            style: {
                files: {
                    // Hmmm, in reverse order
                    "style.css": ["build/_precss/style.css"],
                }
            },
        },

        copy:{
            maps: {
                files: [
                    { expand: true, src: ["build/_precss/style.map"], dest: "", flatten: true, },
                ]
            }
        },
        csslint: {
            main: {
                src: [ "style.css" ],
                options: {
                    "fallback-colors": false,              // unless we want to support IE8
                    "box-sizing": false,                   // unless we want to support IE7
                    "compatible-vendor-prefixes": false,   // The library on this is older than autoprefixer.
                    "gradients": false,                    // This also applies ^
                    "overqualified-elements": false,       // We have weird uses that will always generate warnings.
                    "ids": false,
                    "regex-selectors": false,              // audit
                    "adjoining-classes": false,
                    "box-model": false,                    // audit
                    "universal-selector": false,           // audit
                    "unique-headings": false,              // audit
                    "outline-none": false,                 // audit
                    "floats": false,
                    "font-sizes": false,                   // audit
                    "important": false,                    // This should be set to 2 one day.
                    "unqualified-attributes": false,       // Should probably be 2 one day.
                    "qualified-headings": false,
                    "known-properties": 1,              // Okay to ignore in the case of known unknowns.
                    "duplicate-background-images": 2,
                    "duplicate-properties": 2,
                    "star-property-hack": 2,
                    "text-indent": 2,
                    "display-property-grouping": 2,
                    "shorthand": 2,
                    "empty-rules": false,
                    "vendor-prefix": 2,
                    "zero-units": 2
                }
            }
        },

        clean: {
            options: {
                force: true
            },
            temp: [ 'tmp-style.css', 'tmp-style.css.map' ]
        },

        phpcs: {
            plugin: {
                src: './'
            },
            options: {
                bin: "vendor/bin/phpcs --extensions=php --ignore=\"*/vendor/*,*/node_modules/*\"",
                standard: "phpcs.ruleset.xml"
            }
        },
		phpcbf: {
			options: {
				bin: "vendor/bin/phpcbf --extensions=php --ignore=\"*/vendor/*,*/node_modules/*\"",
                standard: "phpcs.ruleset.xml"
			},
			files: {
			src:'./'
			}
		},

    });

    grunt.loadNpmTasks( "grunt-postcss" );
	grunt.loadNpmTasks( "grunt-sass" );
	grunt.loadNpmTasks( "grunt-contrib-copy" );
	grunt.loadNpmTasks( "grunt-contrib-cssmin" );
    grunt.loadNpmTasks( "grunt-contrib-concat" );
    grunt.loadNpmTasks( "grunt-contrib-csslint" );
    grunt.loadNpmTasks( "grunt-contrib-clean" );
    grunt.loadNpmTasks( "grunt-contrib-watch" );
    grunt.loadNpmTasks( "grunt-phpcs" );
	grunt.loadNpmTasks( 'grunt-phpcbf' );

    // Default task(s).
    grunt.registerTask("default", [/*"concat",*/ "sass", "postcss", "cssmin", "copy", "csslint", "clean", "phpcbf", "phpcs"]);
};
