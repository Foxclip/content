const path = require('path');
module.exports = (env, argv) => {
    const isProduction = argv.mode === 'production';
    return {
        entry: {
            index: './src/ts/index.ts',
            header: './src/ts/header.js',
            create_post: './src/ts/create_post.ts',
            login: './src/ts/login.ts',
            register: './src/ts/register.ts',
            view_profile: './src/ts/view_profile.tsx',
            header: './src/ts/header.ts',
            edit_profile: './src/ts/edit_profile.tsx'
        },
        module: {
            rules: [
                {
                    test: /\.(ts|tsx)$/,
                    use: 'ts-loader',
                    include: [path.resolve(__dirname, 'src/ts')],
                    exclude: /node_modules/,
                },
            ],
        },
        resolve: {
            extensions: ['.ts', '.tsx', '.js'],
        },
        devtool: isProduction ? false : 'source-map',
        output: {
            filename: '[name].js',
            path: path.resolve(__dirname, 'src/public/js'),
            clean: true,
        }
    }
};
