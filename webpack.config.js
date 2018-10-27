/**
 * External dependencies
 */
const webpack = require("webpack");
const ExtractTextPlugin = require("extract-text-webpack-plugin");

// Main CSS loader for everything but blocks..
const cssExtractTextPlugin = new ExtractTextPlugin({
  filename: "./admin/js/[name]/build/style.css"
});

// Configuration for the ExtractTextPlugin.
const extractConfig = {
  use: [
    { loader: "raw-loader" },
    {
      loader: "postcss-loader",
      options: {
        plugins: [require("autoprefixer")]
      }
    },
    {
      loader: "sass-loader",
      query: {
        outputStyle:
         "production" === process.env.NODE_ENV ? "compressed" : "nested"
      }
    }
  ]
};

const entryPointNames = ["cards", "i18n"];

const externals = {
  react: "React"
};
entryPointNames.forEach(entryPointName => {
  externals["@jm-tc/" + entryPointName] = {
    this: ["jm-tc", entryPointName]
  };
});

const wpDependencies = [
  "components",
  "element",
  "blocks",
  "utils",
  "date",
  "data",
  "i18n",
  "editPost",
  "plugins",
  "apiRequest",
  "hooks",
  "compose",
  "editor"
];
wpDependencies.forEach(wpDependency => {
  externals["@wordpress/" + wpDependency] = {
    this: ["wp", wpDependency]
  };
});

const config = {
  entry: entryPointNames.reduce((memo, entryPointName) => {
    memo[entryPointName] = "./admin/js/" + entryPointName + "/index.js";
    return memo;
  }, {}),
  externals,
  output: {
    filename: "admin/js/[name]/build/index.js",
    path: __dirname,
    library: ["jm-tc", "[name]"],
    libraryTarget: "this"
  },
  resolve: {
    modules: [__dirname, "node_modules"]
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: "babel-loader"
      },
      {
        test: /\.s?css$/,
        use: cssExtractTextPlugin.extract(extractConfig)
      }
    ]
  },
  plugins: [
    new webpack.DefinePlugin({
      "process.env.NODE_ENV": JSON.stringify(
       process.env.NODE_ENV || "development"
      )
    }),
    cssExtractTextPlugin,
    new webpack.LoaderOptionsPlugin({
      minimize: process.env.NODE_ENV === "production",
      debug: process.env.NODE_ENV !== "production"
    })
  ],
  stats: {
    children: false
  }
};

switch (process.env.NODE_ENV) {
  case "production":
    config.plugins.push(new webpack.optimize.UglifyJsPlugin());
    break;

  default:
    config.devtool = "source-map";
}

module.exports = config;