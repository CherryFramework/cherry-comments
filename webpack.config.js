const
	path = require('path'),
	webpack = require('webpack');

const PATHS = {
	sources: path.join( __dirname, 'src' ),
	build: path.join( __dirname, 'assets' )
}

module.exports = {
	entry: [ `${ PATHS.sources }/assets/js/app.js`],

	output: {
		path: PATHS.build,
		filename: 'js/min/cherry-comments.min.js'
	},

};
