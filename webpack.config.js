const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
    mode: 'development',
    entry: {
        'js/app': './src/js/app.js',
        'js/inicio': './src/js/inicio.js',
        'js/auth/registro': './src/js/auth/registro.js',
        'js/auth/login': './src/js/auth/login.js',
        'js/auth/envio': './src/js/auth/envio.js',
        'js/auth/cambio': './src/js/auth/cambio.js',
        'js/proyectos/index': './src/js/proyectos/index.js',
        'js/proyectos/epicas': './src/js/proyectos/epicas.js',
        'js/proyectos/tareas': './src/js/proyectos/tareas.js',
        'css/styles': ['./src/scss/app.scss'],
    },
    output: {
        filename: '[name].js',
        path: path.resolve(__dirname, 'public/build'),
        publicPath: '/public/build/'
    },
    plugins: [
        new MiniCssExtractPlugin({
            filename: 'css/styles.css',
            chunkFilename: '[id].css',
        })

    ],
    module: {
        rules: [{
            test: /\.(c|sc|sa)ss$/,
            use: [{
                loader: MiniCssExtractPlugin.loader,
            },
            {
                loader: 'css-loader',
            },
                'sass-loader',
            ],
        },
        {
            test: /\.(png|svg|jpe?g|gif)$/,
            type: 'asset/resource',
        },
        ],
    },
};