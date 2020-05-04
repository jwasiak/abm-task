const VueLoaderPlugin = require('vue-loader/lib/plugin')

module.exports = {
  mode: 'development',
  entry: {
    App: './src/App.js'
  },
  output: {
    path: __dirname + "/public/js/",
    filename: '[name].js'
  },
  plugins: [
        new VueLoaderPlugin()
  ],
  module: {
    rules: [
     {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
          }
          // other vue-loader options go here
        }
      },
    ]
  },

}