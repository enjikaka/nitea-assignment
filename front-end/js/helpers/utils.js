export const html = String.raw;

export function linkStylesheet(metaUrl) {
    return html`<link rel="stylesheet" href="${metaUrl.replace('.js', '.css')}">`;
}