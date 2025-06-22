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
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><use href="/img/icons.svg#edit"></use></svg>
                        <a href="/edit-product.html?productId=${this.getAttribute('product-id')}">Redigera</a>
                    </md-button>
                    <md-button size-variant="small" color-variant="secondary">
                        <svg slot="icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><use href="/img/icons.svg#delete"></use></svg>
                        <a href="/delete-product.html?productId=${this.getAttribute('product-id')}">Ta bort</a>
                    </md-button>
                </footer>
            </article>
        `;

        this.style.viewTransitionName = `product-item-${this.getAttribute('product-id')}`;
    }
}

customElements.define('product-item', ProductItem);