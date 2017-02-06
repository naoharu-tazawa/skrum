const path = require('path');
module.exports = {
  plugins: [
    // your custom plugins
  ],
  module: {
    loaders: [
      // add your custom loaders.
      {
        test: /\.css?$/,
        loaders: [ 'style', 'raw' ],
        include: path.resolve(__dirname, '../public/css')
      }
    ],
  },
  resolve: {
    alias: {
    },
  },
};
