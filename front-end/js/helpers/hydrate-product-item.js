import { renderProductItemElement } from './render-product-item-element.js';

export async function hydrateProductItem($productItem, productId) {
    const hideActions = $productItem.hasAttribute('hide-actions');

    const response = await fetch('http://localhost:8080/products/' + productId);
    const json = await response.json();
    const product = json.data;

    const $newProductItem = renderProductItemElement(product);

    if (hideActions) {
        $newProductItem.setAttribute('hide-actions', '');
    }

    $productItem.replaceWith($newProductItem);
}
