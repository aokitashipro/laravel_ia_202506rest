// vite.config.js
import { defineConfig } from 'vite'
import { resolve } from 'node:path'

export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        main:  resolve(__dirname, 'index.html'),
        login:  resolve(__dirname, 'login.html'),   // ← 追加ページ
        // nested: resolve(__dirname, 'nested/index.html')  のように下層も可
      },
    },
  },
})
