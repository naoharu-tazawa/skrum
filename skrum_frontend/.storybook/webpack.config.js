const path = require('path');
module.exports = {
  plugins: [
    // your custom plugins
  ],
  module: {
    loaders: [
      // add your custom loaders.
      {
        test: /(base|reset)\.css?$/,
        loaders: [ 'style', 'raw' ],
        include: path.resolve(__dirname, '../public/css')
      },
      {
        test: /\.css$/,
        loader: 'style!css?modules'
      }
    ],
  },
  resolve: {
    alias: {
    },
  },
};
