import { renderProductItemElement } from './render-product-item-element.js';

export async function hydrateProductItem($productItem, productId) {
    const response = await fetch('http://localhost:8080/products/' + productId);
    const json = await response.json();
    const product = json.data;

    $productItem.outerHTML = renderProductItemElement(product);
}
