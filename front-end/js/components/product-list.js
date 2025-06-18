import './product-item.js';

import { renderProductItemElement } from '../helpers/render-product-item-element.js';
import { html } from '../helpers/utils.js';

class ProductList extends HTMLElement {
    constructor() {
        super();
        this.products = [];
    }

    async fetchProducts() {
        return fetch('http://localhost:8080/products')
            .then(response => response.json())
            .then(response => {
                this.products = response.data;
            });
    }

    async connectedCallback() {
        await this.fetchProducts();
        this.render();
    }

    render() {
        const sDOM = this.attachShadow({ mode: 'closed' });

        const productItems = this.products
            .map(product => renderProductItemElement(product))
            .map(productItem => `<li>${productItem}</li>`)
            .join('');

        sDOM.innerHTML = html`
            <style>
                ol {
                    list-style: none;
                    display: flex;
                    flex-flow: column nowrap;
                    gap: 1rem;
                    padding: 0 1rem;
                    margin: 0;
                }
            </style>
            <ol>
                ${productItems}
            </ol>
        `;
    }
}

customElements.define('product-list', ProductList);