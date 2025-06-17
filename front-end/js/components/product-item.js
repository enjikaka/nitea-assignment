const html = String.raw;

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
            <style>
                :root {
                    display: block;
                    width: 100%;
                    height: auto;
                }

               article {
                background-color: var(--green);
                display: flex;
                flex-flow: row nowrap;
                gap: 1rem;
                padding: 1rem;
                border-radius: 0.5rem;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
                align-items: center;
               }

               figure {
                margin: 0;
                padding: 0;
                width: 100px;
                height: 100px;
                border-radius: 0.5rem;
                box-shadow: 0 0 10px 0 rgba(0, 0, 0, 0.1);
                background-color: rgba(0, 0, 0, 0.1);
               }

               ::slotted(img:empty) {
                display: none;
               }
            </style>
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
                    <a href="/edit-product.html?productId=${this.getAttribute('product-id')}">Edit</a>
                    <a href="/delete-product.html?productId=${this.getAttribute('product-id')}">Delete</a>
                </footer>
            </article>
        `;
    }
}

customElements.define('product-item', ProductItem);