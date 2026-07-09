(function() {
    // 1. Monkey-patch XMLHttpRequest to inject the pages field into REST save requests
    const XHR = XMLHttpRequest.prototype;
    const open = XHR.open;
    const send = XHR.send;

    XHR.open = function(method, url) {
        this._method = method;
        this._url = url;
        return open.apply(this, arguments);
    };

    XHR.send = function(postData) {
        if (this._method && (this._method.toUpperCase() === 'POST' || this._method.toUpperCase() === 'PUT')) {
            if (this._url && this._url.indexOf('/code-snippets/v1/snippets') !== -1) {
                try {
                    let data = JSON.parse(postData);
                    const pagesInput = document.getElementById('snippet-pages-input');
                    if (pagesInput) {
                        data.pages = pagesInput.value;
                        postData = JSON.stringify(data);
                    }
                } catch(e) {
                    // Ignore JSON parse errors
                }
            }
        }
        return send.apply(this, [postData]);
    };

    // Keep track of the typed pages value across React re-renders
    let currentPagesValue = '';
    if (window.CODE_SNIPPETS_EDIT && window.CODE_SNIPPETS_EDIT.snippet && window.CODE_SNIPPETS_EDIT.snippet.pages) {
        currentPagesValue = window.CODE_SNIPPETS_EDIT.snippet.pages;
    }

    // 2. Poll/watch the DOM until the snippet form / location selector is loaded, then inject our custom field
    function injectPagesField() {
        const targetElement = document.querySelector('.code-snippets-select-location');
        if (!targetElement) return;

        // Check if we already injected it in the current container
        if (document.getElementById('snippet-pages-container')) return;

        // Find the parent block-form-field div of the select location container
        const parentFormField = targetElement.closest('.block-form-field');
        if (!parentFormField) return;

        // Create container for pages input
        const pagesContainer = document.createElement('div');
        pagesContainer.className = 'block-form-field';
        pagesContainer.id = 'snippet-pages-container';
        pagesContainer.style.marginTop = '15px';

        // Set inner HTML
        pagesContainer.innerHTML = `
            <h4><label for="snippet-pages-input">Ejecutar en páginas específicas</label></h4>
            <input type="text" id="snippet-pages-input" class="widefat" placeholder="Ej: 12, contact-us, /blog/*" style="padding: 6px 10px; border-radius: 4px; border: 1px solid #ccc; width: 100%; box-sizing: border-box;" />
            <p class="description" style="margin-top: 5px; font-size: 11px; line-height: 1.4; color: #666;">Introduce IDs de páginas, slugs o rutas URL separados por comas. Ej: <code>15, contact-us, /category/*</code>. Deja vacío para ejecutar en todo el sitio.</p>
        `;

        // Insert after the location selector parent div
        parentFormField.parentNode.insertBefore(pagesContainer, parentFormField.nextSibling);

        // Populate and set up event listeners
        const pagesInput = document.getElementById('snippet-pages-input');
        if (pagesInput) {
            pagesInput.value = currentPagesValue;
            pagesInput.addEventListener('input', function(e) {
                currentPagesValue = e.target.value;
                if (window.CODE_SNIPPETS_EDIT && window.CODE_SNIPPETS_EDIT.snippet) {
                    window.CODE_SNIPPETS_EDIT.snippet.pages = currentPagesValue;
                }
            });
        }
    }

    // Start watching/injecting
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', injectPagesField);
    } else {
        injectPagesField();
    }

    // Set up MutationObserver to handle React updates/re-renders
    const observer = new MutationObserver(function() {
        if (!document.getElementById('snippet-pages-container')) {
            injectPagesField();
        }
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
})();
