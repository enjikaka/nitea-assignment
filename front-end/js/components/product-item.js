import { html, linkStylesheet } from '../helpers/utils.js';

class ProductItem extends HTMLElement {
    constructor() {
        super();
    }

    async connectedCallback() {
        this.render();
    }

    render() {
        const sDOM = this.attachShadow({ mode: 'closed' });

        sDOM.innerHTML = html`
            ${linkStylesheet(import.meta.url)}
            <article>
                <figure>
                    <slot name="image"></slot>
                </figure>
                <div>
                    <strong><slot name="name"></slot></strong>
                    <small><slot name="price"></slot></small>
                    <small><slot name="categories"></slot></small>
                </div>
                <footer>
                    <a href="/edit-product.html?productId=${this.getAttribute('product-id')}">Redigera</a>
                    <a href="/delete-product.html?productId=${this.getAttribute('product-id')}">Delete</a>
                </footer>
            </article>
        `;
    }
}

customElements.define('product-item', ProductItem);