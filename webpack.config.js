const path = require('path');

module.exports = {
    entry: [
        './resources/css/app.scss',
        './resources/js/app.js'
    ],
    mode: 'development',
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'js/bundle.min.js',
    },
    resolve: {
        alias: {
            jquery: "jquery/src/jquery"
        }
    },
    module: {
        rules: [
            {
                test: /\.s[ac]ss$/i,
                use: [
                    {
                        loader: 'file-loader',
                        options: { outputPath: 'css', name: 'bundle.min.css' }
                    },
                    'sass-loader'
                ]
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/i,
                loader: 'url-loader'
            },
        ],
    },
};

