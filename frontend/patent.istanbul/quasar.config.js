/* eslint-env node */

/*
 * This file runs in a Node context (it's NOT transpiled by Babel), so use only
 * the ES6 features that are supported by your Node version. https://node.green/
 */

// Configuration for your app
// https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js

const ESLintPlugin = require("eslint-webpack-plugin");

const { configure } = require("quasar/wrappers");

const SitemapPlugin = require('sitemap-webpack-plugin').default


module.exports = configure(function(ctx) {
    return {
        // https://v2.quasar.dev/quasar-cli-webpack/supporting-ts
        supportTS: false,

        // https://v2.quasar.dev/quasar-cli-webpack/prefetch-feature
        preFetch: true,

        // app boot file (/src/boot)
        // --> boot files are part of "main.js"
        // https://v2.quasar.dev/quasar-cli-webpack/boot-files
        boot: ["axios", "i18n", "event-modifier"],

        // https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-css
        css: ["app.scss"],

        // https://github.com/quasarframework/quasar/tree/dev/extras
        extras: [
            "material-icons", // optional, you are not bound to it
        ],

        // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-build
        build: {
            optimization: {
                usedExports: true,
            },
            mode: "production",
            vueRouterMode: "history", // available values: 'hash', 'history'
            env: {
                URL: "https://patent.istanbul",
                TITLE: "Patist IP Turkey",
                API_URL: "https://api.patent.istanbul",
            },
            // transpile: false,
            // publicPath: '/',

            // Add dependencies for transpiling with Babel (Array of string/regex)
            // (from node_modules, which are by default not transpiled).
            // Applies only if "transpile" is set to true.
            // transpileDependencies: [],

            // rtl: true, // https://quasar.dev/options/rtl-support
            preloadChunks: true,
            // showProgress: false,
            gzip: true,
            // analyze: true,

            // Options below are automatically set depending on the env, set them if you want to override
            // extractCSS: false,
            minify: true,
            // https://v2.quasar.dev/quasar-cli-webpack/handling-webpack
            // "chain" is a webpack-chain object https://github.com/neutrinojs/webpack-chain

            build: {
                optimization: {
                    usedExports: true,
                },
                mode: "production",

            },
            extendWebpack(cfg, { isServer, isClient }) {
                cfg.plugins.push(
                    new SitemapPlugin({
                        base: 'https://patent.istanbul',
                        paths: [
                            '/',
                            '/european-patent-validation-in-turkiye',
                            '/pct-national-phase-entry-in-turkiye',
                            '/patent-renewals-in-turkiye',
                            '/about',
                            '/services',
                            '/FAQs',
                            '/api',
                            '/knowledge-base',
                            '/knowledge-base/european-patent-validation-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-it-possible-to-validate-a-european-patent-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/must-a-national-professional-representative-be-appointed-for-european-patent-validations-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-turkiye-a-contracting-state-to-the-london-agreement',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-the-translation-of-the-description-required-for-european-patent-validations-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/what-is-the-period-for-filing-the-translation-of-a-european-patent-validation-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-late-validation-of-a-european-patent-possible',
                            '/knowledge-base/european-patent-validation-in-turkiye/how-much-does-a-validation-of-a-european-patent-cost-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/what-are-the-official-fees-for-european-patent-validations-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/when-are-the-official-fees-due',
                            '/knowledge-base/european-patent-validation-in-turkiye/how-long-does-it-take-to-validate-a-european-patent-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-power-of-attorney-required-to-validate-a-european-patent-in-turkiye',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-late-filing-of-the-translation-possible',
                            '/knowledge-base/european-patent-validation-in-turkiye/is-correction-of-translation-permitted',
                            '/knowledge-base/european-patent-validation-in-turkiye/what-are-the-official-fees-payable-in-filing-a-corrected-translation',
                        ],
                        options: {
                            filename: 'sitemap.xml',
                            lastmod: true,
                            changefreq: 'yearly',
                        }
                    })
                )
            },
        },

        // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-devServer
        devServer: {
            server: {
                type: "http",
            },
            port: 8080,
            open: true, // opens browser window automatically
        },

        // https://v2.quasar.dev/quasar-cli-webpack/quasar-config-js#Property%3A-framework
        framework: {
            config: {},

            // iconSet: 'material-icons', // Quasar icon set
            // lang: 'en-US', // Quasar language pack

            // For special cases outside of where the auto-import strategy can have an impact
            // (like functional components as one of the examples),
            // you can manually specify Quasar components/directives to be available everywhere:
            //
            // components: [],
            // directives: [],

            // Quasar plugins
            plugins: ["Dialog", "Meta"],
        },

        // animations: 'all', // --- includes all animations
        // https://quasar.dev/options/animations
        animations: "",

        // https://v2.quasar.dev/quasar-cli-webpack/developing-ssr/configuring-ssr
        ssr: {
            pwa: false,
            server: {
                http2: true,
            },
            // manualStoreHydration: true,
            // manualPostHydrationTrigger: true,

            prodPort: 3333, // The default port that the production server should use
            // (gets superseded if process.env.PORT is specified at runtime)

            maxAge: 1000 * 60 * 60 * 24 * 30,
            // Tell browser when a file from the server should expire from cache (in ms)

            chainWebpackWebserver(chain) {
                chain
                    .plugin("eslint-webpack-plugin")
                    .use(ESLintPlugin, [{ extensions: ["js"] }]);
            },

            optimization: {
                usedExports: true,
            },
            mode: "production",

            middlewares: [
                ctx.prod ? "compression" : "",
                "render", // keep this as last one
            ],
        },

        // https://v2.quasar.dev/quasar-cli-webpack/developing-pwa/configuring-pwa
        pwa: {
            workboxPluginMode: "GenerateSW", // 'GenerateSW' or 'InjectManifest'
            workboxOptions: {}, // only for GenerateSW

            // for the custom service worker ONLY (/src-pwa/custom-service-worker.[js|ts])
            // if using workbox in InjectManifest mode

            chainWebpackCustomSW(chain) {
                chain
                    .plugin("eslint-webpack-plugin")
                    .use(ESLintPlugin, [{ extensions: ["js"] }]);
            },

            manifest: {
                name: `Quasar App`,
                short_name: `Quasar App`,
                description: `A Quasar Project`,
                display: "standalone",
                orientation: "portrait",
                background_color: "#ffffff",
                theme_color: "#027be3",
                icons: [{
                        src: "icons/icon-128x128.png",
                        sizes: "128x128",
                        type: "image/png",
                    },
                    {
                        src: "icons/icon-192x192.png",
                        sizes: "192x192",
                        type: "image/png",
                    },
                    {
                        src: "icons/icon-256x256.png",
                        sizes: "256x256",
                        type: "image/png",
                    },
                    {
                        src: "icons/icon-384x384.png",
                        sizes: "384x384",
                        type: "image/png",
                    },
                    {
                        src: "icons/icon-512x512.png",
                        sizes: "512x512",
                        type: "image/png",
                    },
                ],
            },
        },

        // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-cordova-apps/configuring-cordova
        cordova: {
            // noIosLegacyBuildFlag: true, // uncomment only if you know what you are doing
        },

        // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-capacitor-apps/configuring-capacitor
        capacitor: {
            hideSplashscreen: true,
        },

        // Full list of options: https://v2.quasar.dev/quasar-cli-webpack/developing-electron-apps/configuring-electron
        electron: {
            bundler: "packager", // 'packager' or 'builder'

            packager: {
                // https://github.com/electron-userland/electron-packager/blob/master/docs/api.md#options
                // OS X / Mac App Store
                // appBundleId: '',
                // appCategoryType: '',
                // osxSign: '',
                // protocol: 'myapp://path',
                // Windows only
                // win32metadata: { ... }
            },

            builder: {
                // https://www.electron.build/configuration/configuration

                appId: "quasar-project",
            },

            // "chain" is a webpack-chain object https://github.com/neutrinojs/webpack-chain

            chainWebpackMain(chain) {
                chain
                    .plugin("eslint-webpack-plugin")
                    .use(ESLintPlugin, [{ extensions: ["js"] }]);
            },

            chainWebpackPreload(chain) {
                chain
                    .plugin("eslint-webpack-plugin")
                    .use(ESLintPlugin, [{ extensions: ["js"] }]);
            },
        },
    };
});
