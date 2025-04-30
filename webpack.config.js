const path = require('path');
module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    return {
        entry: {
            index: './php/src/ts/index.ts',
            header: './php/src/ts/header.js',
            create_post: './php/src/ts/create_post.ts',
            login: './php/src/ts/login.ts',
            register: './php/src/ts/register.ts',
            view_profile: './php/src/ts/view_profile.ts',
            header: './php/src/ts/header.ts',
            edit_profile: './php/src/ts/edit_profile.ts'
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
