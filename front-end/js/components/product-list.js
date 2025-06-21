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
            .map(productItem => {
                const $listItem = document.createElement('li');
                $listItem.appendChild(productItem);
                return $listItem;
            });

        sDOM.innerHTML = html`
            <style>
                ol {
                    list-style: none;
                    display: flex;
                    flex-flow: column nowrap;
                    padding: 0;
                    margin: 0;
                }

                li {
                    margin: 0;
                    padding: 0;
                }
            </style>
            <ol></ol>
        `;

        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                sDOM.querySelector('ol').append(...productItems);
            });
        });
    }
}

customElements.define('product-list', ProductList);