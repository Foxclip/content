const path = require('path');
module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    return {
        entry: {
            index: './php/src/ts/index.ts',
            header: './php/src/ts/header.js'
        },
        module: {
            rules: [
                {
                    test: /\.ts$/,
                    use: 'ts-loader',
                    include: [path.resolve(__dirname, 'php/src/ts')],
                    exclude: /node_modules/,
                },
            ],
        },
        resolve: {
            extensions: ['.ts', '.js'],
        },
        devtool: isProduction ? false : 'source-map',
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, 'php/src/public/js'),
            clean: true,
        }
    }
};
