import { html, linkStylesheet } from '../helpers/utils.js';

export class MdButton extends HTMLElement {
    connectedCallback() {
        this.sDOM = this.attachShadow({ mode: 'open' });
        this.sDOM.innerHTML = html`
            ${linkStylesheet(import.meta.url)}
            <slot name="icon"></slot>
            <slot></slot>
        `;

        this.addEventListener('click', () => {
            const slotElement = [...this.sDOM.querySelectorAll('slot')].pop();

            if (slotElement instanceof HTMLSlotElement) {
                const assignedNodes = slotElement.assignedNodes();

                for (const node of assignedNodes) {
                    if (node instanceof HTMLAnchorElement || node instanceof HTMLButtonElement) {
                        node.click();
                    }
                }
            }
        });
    }
}

customElements.define('md-button', MdButton);