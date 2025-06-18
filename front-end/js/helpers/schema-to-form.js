/**
 * Converts a JSON schema to an HTML form string
 * @param {Object} schema - The JSON schema object
 * @returns {string} HTML form string
 */
export function schemaToForm(schema) {
    if (!schema || !schema.properties) {
        return '';
    }

    const formFields = Object.entries(schema.properties)
        .map(([key, field]) => {
            const isRequired = schema.required?.includes(key);
            const label = formatLabel(key);
            const input = createInput(key, field, isRequired);
            return `
                <label>${input}<span>${label}</span></label>
            `;
        })
        .join('');

    return `
        <form class="schema-form">
            ${formFields}
            <button type="submit">Submit</button>
        </form>
    `;
}

function formatLabel(key) {
    return key
        .replace(/([A-Z])/g, ' $1')
        .replace(/_/g, ' ')
        .replace(/^./, str => str.toUpperCase());
}

function formatPlaceholder(key) {
    return key
        .replace(/([A-Z])/g, ' $1')
        .replace(/_/g, ' ')
        .replace(/^./, str => str.toUpperCase());
}

function createInput(key, field, isRequired) {
    const commonAttributes = `
        id="${key}"
        name="${key}"
        ${isRequired ? 'required' : ''}
        ${field.description ? `title="${field.description}"` : ''}
    `;

    switch (field.type) {
        case 'string':
            if (key === 'categories') {
                return `
                    <input type="text"
                        ${commonAttributes}
                        placeholder="Enter categories separated by commas"
                    >
                `;
            }
            return `
                <input type="text"
                    ${commonAttributes}
                    ${field.description ? `placeholder="${formatPlaceholder(key)}"` : ''}
                >
            `;

        case 'number':
            return `
                <input type="number"
                    ${commonAttributes}
                    step="0.01"
                    ${field.minimum !== undefined ? `min="${field.minimum}"` : ''}
                >
            `;

        default:
            return `
                <input type="text"
                    ${commonAttributes}
                >
            `;
    }
} 