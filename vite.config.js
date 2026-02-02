import { resolve } from 'path';
import { glob } from 'glob';
import viteImagemin from 'vite-plugin-imagemin';
import imagemin from 'imagemin';
import imageminPngquant from 'imagemin-pngquant';
import imageminSvgo from 'imagemin-svgo';
import { readFileSync, writeFileSync, mkdirSync } from 'fs';
import { basename, dirname } from 'path';

export default {
    root: 'src',
    resolve: {
        alias: {
            '~': resolve(__dirname, 'src')
        }
    },
    plugins: [
        viteImagemin({
            mozjpeg: { quality: 78, progressive: true },
            pngquant: { quality: [0.6, 0.8] },
            gifsicle: { optimizationLevel: 3 },
            svgo: {
                plugins: [
                    { name: 'preset-default', params: { overrides: { removeViewBox: false } } },
                    { name: 'removeDimensions', active: true }
                ]
            }
        }),
        {
            name: 'optimize-all-images',
            apply: 'build',
            async closeBundle() {
                // Оптимизируем все PNG
                const allImages = glob.sync('src/assets/imgs/*.{png,jpg,jpeg}');
                const imgsOutDir = resolve(__dirname, 'assets/imgs');

                for (const imgPath of allImages) {
                    const fileName = basename(imgPath);
                    const destPath = resolve(imgsOutDir, fileName);

                    try {
                        const result = await imagemin([imgPath], {
                            plugins: [
                                imageminPngquant({ quality: [0.6, 0.8] })
                            ]
                        });

                        if (result && result[0]) {
                            mkdirSync(dirname(destPath), { recursive: true });
                            writeFileSync(destPath, result[0].data);
                            console.log(`✓ Optimized PNG: ${fileName}`);
                        }
                    } catch (err) {
                        console.warn(`⚠ Failed to optimize ${fileName}:`, err.message);
                    }
                }

                // Оптимизируем все SVG иконки
                const allIcons = glob.sync('src/assets/icons/*.svg');
                const iconsOutDir = resolve(__dirname, 'assets/icons');

                for (const iconPath of allIcons) {
                    const fileName = basename(iconPath);
                    const destPath = resolve(iconsOutDir, fileName);

                    try {
                        const result = await imagemin([iconPath], {
                            plugins: [
                                imageminSvgo({
                                    plugins: [
                                        { name: 'preset-default', params: { overrides: { removeViewBox: false } } },
                                        { name: 'removeDimensions' }
                                    ]
                                })
                            ]
                        });

                        if (result && result[0]) {
                            mkdirSync(dirname(destPath), { recursive: true });
                            writeFileSync(destPath, result[0].data);
                            console.log(`✓ Optimized SVG: ${fileName}`);
                        }
                    } catch (err) {
                        console.warn(`⚠ Failed to optimize ${fileName}:`, err.message);
                    }
                }
            }
        },
        {
            name: 'replace-css-paths',
            apply: 'build',
            writeBundle() {
                const cssFiles = glob.sync('assets/css/*.css', { cwd: __dirname });
                cssFiles.forEach(cssFile => {
                    const cssPath = resolve(__dirname, cssFile);
                    let content = readFileSync(cssPath, 'utf-8');
                    content = content.replace(/url\(\/assets\/imgs\//g, 'url(../imgs/');
                    content = content.replace(/url\(\/assets\/icons\//g, 'url(../icons/');
                    writeFileSync(cssPath, content);
                    console.log(`✓ CSS paths replaced in ${cssFile}`);
                });
            }
        }
    ],
    server: {
        host: true,
        port: 5173,
        strictPort: true,
        hmr: {
            protocol: 'ws',
            port: 5173
        },
        watch: {
            usePolling: true,
            interval: 100,
            awaitWriteFinish: { stabilityThreshold: 200, pollInterval: 100 }
        }
    },
    build: {
        outDir: '../',
        assetsDir: 'assets',
        emptyOutDir: false,
        sourcemap: false,
        manifest: true,
        assetsInlineLimit: 0,
        rollupOptions: {
            input: {
                main: resolve(__dirname, 'src/js/main.js'),
                style: resolve(__dirname, 'src/sass/style.scss')
            },
            output: {
                assetFileNames: (assetInfo) => {
                    if (assetInfo.name && assetInfo.name.endsWith('.css')) {
                        return 'assets/css/[name].[hash][extname]';
                    }
                    if (assetInfo.name && (assetInfo.name.includes('icon') || assetInfo.name.endsWith('.svg'))) {
                        return 'assets/icons/[name][extname]';
                    }
                    if (/\.(png|jpe?g|gif|webp|bmp|ico)$/i.test(assetInfo.name || '')) {
                        return 'assets/imgs/[name][extname]';
                    }
                    return 'assets/[name].[hash][extname]';
                },
                chunkFileNames: 'assets/js/[name].[hash].js',
                entryFileNames: 'assets/js/[name].[hash].js'
            }
        }
    }
};



