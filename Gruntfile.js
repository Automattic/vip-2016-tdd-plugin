/* jshint node:true */
module.exports = function( grunt ) {
	grunt.initConfig({
		qunit: {
			all: {
			}
		}
	});
	grunt.loadNpmTasks( 'grunt-contrib-qunit' );
	grunt.registerTask( 'default', [ 'qunit' ] );
	grunt.registerTask( 'test', [ 'qunit:all' ] );
};