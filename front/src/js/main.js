import { indexProducts } from './products/index';
import { showProduct } from './products/show';

// 第1引数はURL, 第2引数はHTMLのid
indexProducts('http://localhost:8000/api/products', 'products-index');


showProduct('http://localhost:8000/api/products/1', 'product-show');
