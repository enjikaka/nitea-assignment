import '../components/app-header.js';
import '../components/edit-form.js';
import '../components/product-item.js';

import { renderProductItemElement } from '../helpers/render-product-item-element.js';
import { hydrateProductItem } from '../helpers/hydrate-product-item.js';
import { html } from '../helpers/utils.js';

const qp = new URLSearchParams(document.location.search);

const id = qp.get('productId') ?? qp.get('id');

const $editForm = document.querySelector('edit-form');
const $productItem = document.querySelector('product-item');

if (qp.size === 1) {
    $editForm.setAttribute('entity-id', id);
    $productItem.setAttribute('product-id', id);

    const response = await fetch('http://localhost:8080/products/' + id);
    const json = await response.json();
    const product = json.data;

    $editForm.fillForm(product);
    hydrateProductItem($productItem, id);
} else { // Form is submitted
    $editForm.remove();
    $productItem.remove();

    const $main = document.querySelector('main');
    $main.innerHTML += html`<output></output>`;
    const $output = document.querySelector('output');

    $output.textContent = 'Uppdaterar produkt...';

    // Convert categories string to array
    const categoriesString = qp.get('categories');
    const categories = categoriesString ? categoriesString.split(',').map(cat => cat.trim()).filter(cat => cat.length > 0) : [];

    const response = await fetch('http://localhost:8080/products/' + id, {
        method: 'PATCH',
        body: JSON.stringify({
            name: qp.get('name'),
            price: parseFloat(qp.get('price')),
            categories: categories,
            image: qp.get('image'),
        }),
        headers: {
            'Content-Type': 'application/json',
        },
    });

    const json = await response.json();
    const updatedProduct = json.data;

    $output.textContent = 'Produkt uppdaterat:';
    $main.append(renderProductItemElement(updatedProduct));

    const $newProductItem = document.querySelector('product-item');

    $newProductItem.setAttribute('hide-actions', 'hide-actions');
}
