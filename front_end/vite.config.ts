import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'
import * as path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [react()],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  },
  server: {
    host: true,
    port: 3004,
    strictPort: true,
    proxy:{
      '/api':{
        target:'http://127.0.0.1:8000',
        changeOrigin:true,
        secure: false,
        headers:{
          Accept:'application/json',
          'Content-Type':'application/json'
        }
      }
  }
  },
  build: {
    outDir: 'localhost',
    emptyOutDir: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'index.html')
      }
    }
  }
})
