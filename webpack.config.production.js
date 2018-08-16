const LodashModuleReplacementPlugin = require('lodash-webpack-plugin');
const path = require('path');

module.exports = {
	mode: 'production',
	entry: ['./assets/js/src/functions.js'],
	output: {
		path: path.resolve(__dirname, 'assets'),
		filename: 'js/functions.js',
	},
	module: {
		rules: [
			/**
			 * Running Babel on JS files.
			 */
			{
				test: /\.js$/,
				exclude: /node_modules/,
				use: {
					loader: 'babel-loader',
					options: {
						plugins: ['lodash'],
						presets: ['@wordpress/default']
					}
				}
			}
		]
	},
	'plugins': [
		new LodashModuleReplacementPlugin,
	]
};
