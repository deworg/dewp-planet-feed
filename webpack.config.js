const path = require('path');
const LodashModuleReplacementPlugin = require('lodash-webpack-plugin');
module.exports = {
	mode: 'development',
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
