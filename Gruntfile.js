/* jshint node:true */
module.exports = function( grunt ) {
	grunt.initConfig({
		qunit: {
			all: {
				options: {
					urls: [ 'http://src.wordpress-develop.dev:80/wp-content/plugins/infinity/tests/js/tests.html' ]
				}
			}
		}
	});
	grunt.loadNpmTasks( 'grunt-contrib-qunit' );
	grunt.registerTask( 'default', [ 'qunit' ] );
	grunt.registerTask( 'test', [ 'qunit:all' ] );
};