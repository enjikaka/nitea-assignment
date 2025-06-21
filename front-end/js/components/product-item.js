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
                    <md-button size-variant="small" color-variant="secondary">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polygon points="128 160 96 160 96 128 192 32 224 64 128 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="168" y1="56" x2="200" y2="88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M216,128v80a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V48a8,8,0,0,1,8-8h80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                        <a href="/edit-product.html?productId=${this.getAttribute('product-id')}">Redigera</a>
                    </md-button>
                    <md-button size-variant="small" color-variant="secondary">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><rect width="256" height="256" fill="none"/><polygon points="128 160 96 160 96 128 192 32 224 64 128 160" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><line x1="168" y1="56" x2="200" y2="88" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/><path d="M216,128v80a8,8,0,0,1-8,8H48a8,8,0,0,1-8-8V48a8,8,0,0,1,8-8h80" fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="16"/></svg>
                        <a href="/delete-product.html?productId=${this.getAttribute('product-id')}">Ta bort</a>
                    </md-button>
                </footer>
            </article>
        `;
    }
}

customElements.define('product-item', ProductItem);