const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

module.exports = {
    // Webpack yapılandırma ayarlarınızı buraya ekleyin
    // ...
    plugins: [
        new BundleAnalyzerPlugin()
    ]
};