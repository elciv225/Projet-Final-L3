/**
 * Système AJAX avancé - Support des vues complètes, fragments ET navigation
 * - Gestion des réponses JSON et HTML
 * - Mise à jour du DOM et de l'URL
 * - Navigation dynamique avec liens AJAX
 * - Loader spécifique aux conteneurs
 * - Compatible avec l'ancien code et le CSS existant
 */

// CORRECTION : On crée un registre global pour les fonctions à ré-attacher.
// Chaque page pourra ajouter sa propre fonction d'initialisation ici.
window.ajaxRebinders = window.ajaxRebinders || [];

document.addEventListener('DOMContentLoaded', function () {
    // Initialisation
    createGlobalElements();
    bindAjaxForms();
    bindAjaxLinks();
    bindHistoryEvents();

    // CORRECTION : On exécute toutes les fonctions d'initialisation au premier chargement.
    executeRebinders();
});

// -------------------------------------------------------------------
// Fonctions existantes (conservées)
// -------------------------------------------------------------------
function createGlobalElements() {
    const documentBody = document.querySelector("#content-area .main-content") || document.querySelector("#content-area") || document.body;

    if (!document.getElementById('ajax-popup')) {
        const popup = document.createElement('div');
        popup.id = 'ajax-popup';
        popup.className = 'popup';
        popup.style.display = 'none';
        popup.innerHTML = `
            <div class="popup-content">
                <span id="popup-message"></span>
                <button class="close-popup">&times;</button>
            </div>
        `;
        documentBody.appendChild(popup);

        popup.querySelector('.close-popup').addEventListener('click', closePopup);
    }

    if (!document.getElementById('ajax-loader')) {
        const loader = document.createElement('div');
        loader.id = 'ajax-loader';
        loader.className = 'loader-overlay';
        loader.style.display = 'none';
        loader.innerHTML = '<div class="loader"></div>';
        documentBody.appendChild(loader);
    }

    if (!document.getElementById('warning-card')) {
        const warningCard = document.createElement('div');
        warningCard.id = 'warning-card';
        warningCard.className = 'warning-overlay';
        warningCard.style.display = 'none';
        warningCard.innerHTML = `
            <div class="warning-card">
                <div class="warning-header">
                    <span class="warning-icon">⚠</span>
                    <h3>Attention</h3>
                </div>
                <div class="warning-content">
                    <p id="warning-message"></p>
                </div>
                <div class="warning-actions">
                    <button id="warning-cancel" class="btn-secondary">Annuler</button>
                    <button id="warning-continue" class="btn-primary">Continuer</button>
                </div>
            </div>
        `;
        documentBody.appendChild(warningCard);
    }
}

function showPopup(message, type = 'info') {
    const popup = document.getElementById('ajax-popup');
    if (!popup) return;

    popup.className = `popup ${type}`;
    popup.querySelector('#popup-message').textContent = message;
    popup.style.display = 'block';

    setTimeout(() => {
        popup.style.animation = 'fade-out 0.5s ease-out';
        setTimeout(() => {
            popup.style.display = 'none';
            popup.style.animation = '';
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
        loader.style.display = 'flex';
    }
}

function hideLoader() {
    const loader = document.getElementById('ajax-loader');
    if (loader) {
        loader.style.display = 'none';
    }
}


// -------------------------------------------------------------------
// NOUVELLES FONCTIONS - Navigation AJAX
// -------------------------------------------------------------------
function bindAjaxLinks() {
    document.addEventListener('click', async function (e) {
        const link = e.target.closest('.nav-link-ajax');
        if (!link) return;

        e.preventDefault();
        await handleAjaxNavigation(link);
    });
}

async function handleAjaxNavigation(link) {
    const url = link.href;
    const target = link.dataset.target || '#content-area';

    if (!url || url === '#') return;

    updateActiveNavigation(link);
    showLoader();

    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'text/html, application/json'
            }
        });

        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }

        const contentType = response.headers.get('content-type');
        let data;

        if (contentType?.includes('application/json')) {
            data = await response.json();
        } else {
            data = {html: await response.text()};
        }

        await handleNavigationResponse(data, url, target);

    } catch (error) {
        console.error('Erreur de navigation AJAX:', error);
        showPopup('Erreur lors du chargement de la page', 'error');
    } finally {
        hideLoader();
    }
}

async function handleNavigationResponse(data, url, target) {
    const container = document.querySelector(target);

    if (!container) {
        showPopup('Conteneur cible introuvable', 'error');
        return;
    }

    if (data.html) {
        let content = data.html;

        if (isFullPage(content)) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const mainContent = tempDiv.querySelector('.main-content-wrapper') || tempDiv.querySelector('.main-content');
            content = mainContent ? mainContent.outerHTML : content;
        }

        container.innerHTML = content;

        if (url !== window.location.href) {
            window.history.pushState({url, target}, '', url);
        }

        rebindEventsInContainer(container);
    }

    if (data.statut) {
        const messageType = data.statut === 'succes' ? 'success' : data.statut;
        showPopup(data.message || 'Action terminée', messageType);
    }
}

function updateActiveNavigation(activeLink) {
    document.querySelectorAll('.nav-link-ajax').forEach(link => {
        link.classList.remove('active');
    });
    activeLink.classList.add('active');
}

/**
 * CORRECTION : Nouvelle fonction centrale pour exécuter tous les "rebinders"
 */
function executeRebinders() {
    if (window.ajaxRebinders && Array.isArray(window.ajaxRebinders)) {
        window.ajaxRebinders.forEach(callback => {
            if (typeof callback === 'function') {
                try {
                    callback();
                } catch (e) {
                    console.error("Erreur lors de l'exécution d'un rebinder AJAX:", e);
                }
            }
        });
    }
}

function rebindEventsInContainer(container) {
    container.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            await submitAjaxForm(this);
        });
    });

    container.querySelectorAll('.nav-link-ajax').forEach(link => {
        link.addEventListener('click', async function (e) {
            e.preventDefault();
            await handleAjaxNavigation(this);
        });
    });

    // CORRECTION : On appelle la fonction centrale.
    executeRebinders();

    reloadContainerScripts(container);
}

function reloadContainerScripts(container) {
    container.querySelectorAll('script').forEach(script => {
        if (script.textContent.trim()) {
            try {
                // Utiliser new Function() est plus sûr que eval()
                new Function(script.textContent)();
            } catch (error) {
                console.warn('Erreur lors de l\'exécution du script:', error);
            }
        }
    });
}

// -------------------------------------------------------------------
// Fonctions modifiées pour la compatibilité
// -------------------------------------------------------------------
function bindAjaxForms() {
    document.addEventListener('submit', async function (e) {
        if (!e.target.classList.contains('ajax-form')) return;
        e.preventDefault();
        await submitAjaxForm(e.target);
    });
}

async function submitAjaxForm(form) {
    const action = form.action || window.location.href;
    const method = form.method || 'POST';
    const formData = new FormData(form);
    const submitButton = form.querySelector('button[type="submit"]');
    const target = form.dataset.target || null;

    const warningMessage = form.dataset.warning || form.getAttribute('warning');
    if (warningMessage && !form.dataset.warningConfirmed) {
        showWarningCard(warningMessage, () => {
            form.dataset.warningConfirmed = 'true';
            submitAjaxForm(form);
        });
        return;
    }

    if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    }

    if (target) {
        showLoader();
    }

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
        if (target) {
            hideLoader();
        } else {
            hideLoader();
        }

        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }

        if (form.dataset.warningConfirmed) {
            delete form.dataset.warningConfirmed;
        }
    }
}

async function handleResponse(response, form) {
    const contentType = response.headers.get('content-type');
    let data;

    if (contentType?.includes('application/json')) {
        data = await response.json();
    } else if (contentType?.includes('text/html')) {
        data = {html: await response.text()};
    } else {
        throw new Error('Type de réponse non supporté');
    }

    if (data.redirect) {
        window.location.href = data.redirect;
        return;
    }

    if (data.html) {
        if (isFullPage(data.html)) {
            replaceFullPage(data.html, form.action);
        } else {
            updateContent(data.html, form);
        }
    }

    if (data.statut === 'succes' || data.statut === 'success') {
        showPopup(data.message || 'Opération réussie', 'success');
        const callback = form.dataset.callback;
        if (callback && window[callback]) {
            window[callback](data);
        }
        if (form.dataset.reset !== 'false') {
            form.reset();
        }
        if (data.redirect) {
            setTimeout(() => {
                window.location.href = data.redirect;
            }, data.redirectDelay || 2000);
        }
    } else if (data.statut === 'error') {
        showPopup(data.message || 'Une erreur est survenue', 'error');
    } else if (data.statut === 'warning') {
        showPopup(data.message || 'Avertissement', 'warning');
    } else if (data.statut === 'info') {
        showPopup(data.message || 'Information', 'info');
    }
}

function isFullPage(html) {
    return html.includes('<!DOCTYPE html>') || html.includes('<html');
}

function replaceFullPage(html, url) {
    const bodyContent = html.split('<body>')[1]?.split('</body>')[0] || html;
    document.body.innerHTML = bodyContent;

    if (url && url !== window.location.href) {
        window.history.pushState({}, '', url);
    }

    createGlobalElements();
    bindAjaxForms();
    bindAjaxLinks();

    // CORRECTION : On appelle la fonction centrale.
    executeRebinders();

    reloadScripts();
    showPopup('Page mise à jour', 'success');
}

function updateContent(html, form) {
    const target = form.dataset.target || '#content-area';
    const container = document.querySelector(target);

    if (container) {
        container.innerHTML = html;
        if (form.action !== window.location.href) {
            window.history.pushState({}, '', form.action);
        }
        rebindEventsInContainer(container);
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

function bindHistoryEvents() {
    window.addEventListener('popstate', async (e) => {
        if (e.state && e.state.url) {
            const target = e.state.target || '#content-area';
            showLoader();
            try {
                const response = await fetch(e.state.url, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const html = await response.text();
                await handleNavigationResponse({html}, e.state.url, target);
                const activeLink = document.querySelector(`[href="${e.state.url}"]`);
                if (activeLink) {
                    updateActiveNavigation(activeLink);
                }
            } catch (error) {
                console.error('Erreur de navigation historique:', error);
                showPopup('Erreur de navigation', 'error');
            } finally {
                hideLoader();
            }
        }
    });
}

// -------------------------------------------------------------------
// Fonctions de convenance pour l'API publique
// -------------------------------------------------------------------
function showWarningCard(message, onConfirm) {
    const warningCard = document.getElementById('warning-card');
    const warningMessage = document.getElementById('warning-message');
    const continueBtn = document.getElementById('warning-continue');
    const cancelBtn = document.getElementById('warning-cancel');

    if (!warningCard || !warningMessage) return;

    warningMessage.textContent = message;
    warningCard.style.display = 'flex';

    const handleContinue = () => {
        hideWarningCard();
        if (onConfirm) onConfirm();
        cleanup();
    };

    const handleCancel = () => {
        hideWarningCard();
        cleanup();
    };

    const handleOverlayClick = (e) => {
        if (e.target === warningCard) {
            handleCancel();
        }
    };

    const cleanup = () => {
        continueBtn.removeEventListener('click', handleContinue);
        cancelBtn.removeEventListener('click', handleCancel);
        warningCard.removeEventListener('click', handleOverlayClick);
    };

    continueBtn.addEventListener('click', handleContinue);
    cancelBtn.addEventListener('click', handleCancel);
    warningCard.addEventListener('click', handleOverlayClick);
}

function hideWarningCard() {
    const warningCard = document.getElementById('warning-card');
    if (warningCard) {
        warningCard.style.display = 'none';
    }
}

// -------------------------------------------------------------------
// API publique (pour rétrocompatibilité)
// -------------------------------------------------------------------
window.ajaxRequest = async function (url, options = {}) {
    const method = options.method || 'GET';
    const shouldShowLoader = options.showLoader !== false;
    const target = options.target;

    if (shouldShowLoader) {
        if (target) {
            showLoader();
        } else {
            showLoader();
        }
    }

    try {
        const response = await fetch(url, {
            method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html',
                ...(method !== 'GET' && {'Content-Type': 'application/json'})
            },
            body: method === 'GET' ? null : JSON.stringify(options.data)
        });

        const contentType = response.headers.get('content-type');
        const data = contentType?.includes('application/json')
            ? await response.json()
            : await response.text();

        if (typeof data === 'object' && data !== null) {
            if (data.statut === 'succes') {
                if (options.success) options.success(data);
                else showPopup(data.message || 'Opération réussie', 'success');
            } else if (data.statut === 'error') {
                if (options.error) options.error(data);
                else showPopup(data.message || 'Une erreur est survenue', 'error');
            } else {
                if (options.success) options.success(data);
            }
        } else {
            if (options.success) options.success(data);
        }

    } catch (error) {
        if (options.error) options.error(error);
        else showPopup('Erreur serveur', 'error');
    } finally {
        if (shouldShowLoader) {
            if (target) {
                hideLoader();
            } else {
                hideLoader();
            }
        }
    }
};

// Export des fonctions pour l'API publique
window.showPopup = showPopup;
window.closePopup = closePopup;
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.showWarningCard = showWarningCard;
window.hideWarningCard = hideWarningCard;
