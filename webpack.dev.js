const path = require("path")

module.exports = {
    entry: {
        main: "./src/js/main.js"
    },
    mode: "development",
    output: {
        filename: "[name]-bundle.js",
        path: path.resolve(__dirname, "./public/js"),
        publicPath: 'http://localhost:7878/public/'
    },
    devServer: {
        contentBase: "public/js",
        overlay: true
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    {
                        loader: "style-loader"
                    },
                    {
                        loader: "css-loader"
                    }
                ]
            }        
        ]        
    }
}