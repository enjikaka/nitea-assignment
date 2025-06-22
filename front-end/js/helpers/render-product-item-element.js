import { html } from '../helpers/utils.js';

export function renderProductItemElement(product) {
    const range = document.createRange();

    // Handle categories as array or string
    const categories = Array.isArray(product.categories)
        ? product.categories.join(', ')
        : product.categories;

    const fragment = range.createContextualFragment(html`
        <product-item product-id="${product.id}">
            <span slot="name">${product.name}</span>
            <span slot="price">${product.price}</span>
            <span slot="categories">${categories}</span>
            <img slot="image" src="${product.image}" alt="${product.name}">
        </product-item>
    `);

    return fragment.querySelector('product-item');
}
