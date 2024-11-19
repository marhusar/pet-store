import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
// https://vite.dev/config/
export default defineConfig({
  plugins: [vue()],
  server: {
    host: '0.0.0.0',
    proxy: {
      '/api': {
        target: 'http://host.docker.internal:80', // The base URL of your backend API
        changeOrigin: true,  // Set 'Origin' header correctly for the API
        rewrite: (path) => path.replace(/^\/api/, '/api/v3'), // Rewrite to match your backend URL
      },
    },
  },
});
