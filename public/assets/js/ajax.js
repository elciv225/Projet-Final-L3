/**
 * Système AJAX avancé - Support des vues complètes et fragments
 * - Gestion des réponses JSON et HTML
 * - Mise à jour du DOM et de l'URL
 * - Compatible avec l'ancien code et le CSS existant
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    createGlobalElements();
    bindAjaxForms();
    bindHistoryEvents();
});

// -------------------------------------------------------------------
// Fonctions existantes (corrigées pour respecter le CSS)
// -------------------------------------------------------------------
function createGlobalElements() {
    // Popup - Structure corrigée pour correspondre au CSS
    if (!document.getElementById('ajax-popup')) {
        const popup = document.createElement('div');
        popup.id = 'ajax-popup';
        popup.className = 'popup';
        popup.style.display = 'none'; // Respecte le CSS existant
        popup.innerHTML = `
            <div class="popup-content">
                <span id="popup-message"></span>
                <button class="close-popup">&times;</button>
            </div>
        `;
        document.body.appendChild(popup);

        // Fermeture du popup
        popup.querySelector('.close-popup').addEventListener('click', closePopup);
    }

    // Loader - Structure corrigée
    if (!document.getElementById('ajax-loader')) {
        const loader = document.createElement('div');
        loader.id = 'ajax-loader';
        loader.className = 'loader-overlay';
        loader.style.display = 'none'; // Respecte le CSS existant
        loader.innerHTML = '<div class="loader"></div>';
        document.body.appendChild(loader);
    }
}

function showPopup(message, type = 'info') {
    const popup = document.getElementById('ajax-popup');
    if (!popup) return;

    // Remise à zéro des classes puis ajout du type
    popup.className = `popup ${type}`;
    popup.querySelector('#popup-message').textContent = message;
    popup.style.display = 'block'; // Utilise display au lieu de classList

    // Auto-fermeture après 3 secondes avec animation
    setTimeout(() => {
        popup.style.animation = 'fade-out 0.5s ease-out';
        setTimeout(() => {
            popup.style.display = 'none';
            popup.style.animation = ''; // Reset animation
        }, 500);
    }, 3000);
}

function closePopup() {
    const popup = document.getElementById('ajax-popup');
    if (popup) {
        popup.style.animation = 'fade-out 0.5s ease-out';
        setTimeout(() => {
            popup.style.display = 'none';
            popup.style.animation = '';
        }, 500);
    }
}

function showLoader() {
    const loader = document.getElementById('ajax-loader');
    if (loader) {
        loader.style.display = 'flex'; // Utilise flex comme dans le CSS
    }
}

function hideLoader() {
    const loader = document.getElementById('ajax-loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

// -------------------------------------------------------------------
// Fonctions modifiées/ajoutées
// -------------------------------------------------------------------
function bindAjaxForms() {
    document.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            await submitAjaxForm(this);
        });
    });
}

async function submitAjaxForm(form) {
    const action = form.action || window.location.href;
    const method = form.method || 'POST';
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');

    // Animation du bouton de soumission
    if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    }

    showLoader();

    try {
        const response = await fetch(action, {
            method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html'
            }
        });

        await handleResponse(response, form);

    } catch (error) {
        showPopup('Erreur réseau', 'error');
        console.error('Erreur AJAX:', error);
    } finally {
        hideLoader();

        // Restauration du bouton
        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }
    }
}

async function handleResponse(response, form) {
    const contentType = response.headers.get('content-type');
    let data;

    // Détection du type de réponse
    if (contentType?.includes('application/json')) {
        data = await response.json();
    } else if (contentType?.includes('text/html')) {
        data = { html: await response.text() };
    } else {
        throw new Error('Type de réponse non supporté');
    }

    // Traitement unifié
    if (data.redirect) {
        window.location.href = data.redirect; // Redirection classique
    }
    else if (data.html) {
        if (isFullPage(data.html)) {
            replaceFullPage(data.html, form.action); // Vue complète
        } else {
            updateContent(data.html, form); // Fragment HTML
        }
    }
    else if (data.status === 'success') {
        handleSuccess(data, form); // Ancienne logique JSON
    }
    else if (data.status === 'error') {
        showPopup(data.message || 'Erreur', 'error');
    }
}

function isFullPage(html) {
    return html.includes('<!DOCTYPE html>') || html.includes('<html');
}

function replaceFullPage(html, url) {
    // Extraire le contenu du <body>
    const bodyContent = html.split('<body>')[1]?.split('</body>')[0] || html;
    document.body.innerHTML = bodyContent;

    // Mettre à jour l'URL
    if (url && url !== window.location.href) {
        window.history.pushState({}, '', url);
    }

    // Recréer les éléments globaux après remplacement du body
    createGlobalElements();

    // Rebind les événements
    bindAjaxForms();

    // Ré-exécuter les scripts
    reloadScripts();

    showPopup('Page mise à jour', 'success');
}

function updateContent(html, form) {
    const target = form.dataset.target || '#main-content';
    const container = document.querySelector(target);

    if (container) {
        container.innerHTML = html;

        if (form.action !== window.location.href) {
            window.history.pushState({}, '', form.action);
        }

        // Rebind les nouveaux formulaires AJAX dans le contenu mis à jour
        container.querySelectorAll('form.ajax-form').forEach(newForm => {
            newForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitAjaxForm(this);
            });
        });

        showPopup('Contenu mis à jour', 'success');
    } else {
        showPopup('Conteneur cible introuvable', 'error');
    }
}

function reloadScripts() {
    document.querySelectorAll('script').forEach(oldScript => {
        if (oldScript.src || oldScript.type === 'module') {
            const newScript = document.createElement('script');
            Array.from(oldScript.attributes).forEach(attr => {
                newScript.setAttribute(attr.name, attr.value);
            });
            newScript.textContent = oldScript.textContent;
            oldScript.parentNode.replaceChild(newScript, oldScript);
        }
    });
}

function handleSuccess(data, form) {
    showPopup(data.message || 'Succès', 'success');

    // Callback personnalisé
    const callback = form.dataset.callback;
    if (callback && window[callback]) {
        window[callback](data);
    }

    // Réinitialisation du formulaire
    if (form.dataset.reset !== 'false') {
        form.reset();
    }
}

function bindHistoryEvents() {
    window.addEventListener('popstate', async () => {
        showLoader();
        try {
            const response = await fetch(window.location.href, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            replaceFullPage(html, window.location.href);
        } catch (error) {
            console.error('Erreur de navigation:', error);
            showPopup('Erreur de navigation', 'error');
        } finally {
            hideLoader();
        }
    });
}

// -------------------------------------------------------------------
// API publique (pour rétrocompatibilité)
// -------------------------------------------------------------------
window.ajaxRequest = async function(url, options = {}) {
    const method = options.method || 'GET';
    const shouldShowLoader = options.showLoader !== false;

    if (shouldShowLoader) showLoader();

    try {
        const response = await fetch(url, {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html',
                ...(method !== 'GET' && { 'Content-Type': 'application/json' })
            },
            body: method === 'GET' ? null : JSON.stringify(options.data)
        });

        const contentType = response.headers.get('content-type');
        const data = contentType?.includes('application/json')
            ? await response.json()
            : await response.text();

        if (options.success) options.success(data);

    } catch (error) {
        if (options.error) options.error(error);
        else showPopup('Erreur serveur', 'error');
    } finally {
        if (shouldShowLoader) hideLoader();
    }
};

window.showPopup = showPopup;
window.closePopup = closePopup;
window.showLoader = showLoader;
window.hideLoader = hideLoader;