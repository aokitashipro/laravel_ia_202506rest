// vite.config.js
import { defineConfig } from 'vite'
import { resolve } from 'node:path'

export default defineConfig({
  build: {
    rollupOptions: {
      input: {
        main:  resolve(__dirname, 'index.html'),
        // Products RESTful ページ
        productsIndex: resolve(__dirname, 'products/index.html'),
        productsCreate: resolve(__dirname, 'products/create.html'),

        // Books RESTful ページ
        booksIndex: resolve(__dirname, 'books/index.html'),
        booksCreate: resolve(__dirname, 'books/create.html'),
        booksShow: resolve(__dirname, 'books/show.html'),
        booksEdit: resolve(__dirname, 'books/edit.html'),


        login:  resolve(__dirname, 'login.html'),   // ← 追加ページ
        
      },
    },
  },
})
