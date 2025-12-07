import { defineConfig } from "vite"

export default defineConfig({
    server: {
        //host: '127.0.0.1',  
        root: '/src/frontend',
        host: '0.0.0.0',
        port: 3000,
        watch: {
            usePolling: true, // ファイル変更をポーリングで検知する
        }
    },
    build: {
        //outDir: '/src/public',
        outDir: 'dist',
        manifest: true,
        emptyOutDir: true,
        rollupOptions: {
            output: {
                entryFileNames: 'js/[name].[hash].js',
                chunkFileNames: 'js/[name].[hash].js',
                assetFileNames: ({ name }) => {
                    if (name && name.endsWith('.css')) {
                        return 'css/[name].[hash][extname]';
                    }
                    return 'assets/[name].[hash][extname]';
                }
            }
        }
    }
})