/**
 * Système AJAX avancé - Support des vues complètes, fragments ET navigation
 * - Gestion des réponses JSON et HTML
 * - Mise à jour du DOM et de l'URL
 * - Navigation dynamique avec liens AJAX
 * - Loader spécifique aux conteneurs
 * - Compatible avec l'ancien code et le CSS existant
 */

window.ajaxRebinders = window.ajaxRebinders || [];

document.addEventListener('DOMContentLoaded', function () {
    // Initialisation
    createGlobalElements();
    bindAjaxForms();
    bindAjaxLinks();
    bindHistoryEvents();
    executeRebinders();
});

// -------------------------------------------------------------------
// Fonctions de base (Popups, Loader, etc.)
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

    // --- NOUVEAU LOADER DE NAVIGATION (BARRE DE PROGRESSION) ---
    if (!document.getElementById('ajax-progress-loader')) {
        const progressLoader = document.createElement('div');
        progressLoader.id = 'ajax-progress-loader';
        Object.assign(progressLoader.style, {
            position: 'fixed',
            top: '0',
            left: '0',
            width: '100%',
            height: '4px',
            backgroundColor: 'transparent',
            zIndex: '9999',
            display: 'none',
            pointerEvents: 'none'
        });

        progressLoader.innerHTML = `<div id="ajax-progress-bar"></div>`;
        document.body.appendChild(progressLoader);
    }

    // --- STYLE POUR L'EFFET DE FLOU ---
    if (!document.getElementById('ajax-blur-style')) {
        const style = document.createElement('style');
        style.id = 'ajax-blur-style';
        style.textContent = `
            .ajax-content-loading {
                filter: blur(4px);
                transition: filter 0.3s ease-in-out;
            }
        `;
        document.head.appendChild(style);
    }
}


function showPopup(message, type = 'info') {
    const popup = document.getElementById('ajax-popup');
    if (!popup) return;

    popup.className = `popup ${type}`;
    popup.querySelector('#popup-message').textContent = message;

    popup.style.display = 'block';

    gsap.to(popup, {
        duration: 0.5,
        opacity: 1,
        x: 0,
        scale: 1,
        ease: 'power3.out'
    });

    gsap.to(popup, {
        delay: 3,
        duration: 0.6,
        opacity: 0,
        x: '100%',
        scale: 0.9,
        ease: 'power2.in',
        onComplete: () => {
            popup.style.display = 'none';
            gsap.set(popup, {opacity: 0, x: '20px', scale: 0.95});
        }
    });
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


/**
 * Affiche un loader.
 * @param {string|null} targetSelector - Le sélecteur du conteneur où afficher le loader. Si null, affiche le loader global.
 */
function showLoader(targetSelector = null) {
    if (targetSelector) {
        const container = document.querySelector(targetSelector);
        if (!container) {
            console.error(`Loader target '${targetSelector}' not found. Falling back to global loader.`);
            showLoader(); // Fallback au loader global si la cible n'existe pas
            return;
        }

        if (getComputedStyle(container).position === 'static') {
            container.style.position = 'relative';
        }

        const loaderOverlay = document.createElement('div');
        loaderOverlay.className = 'container-loader-overlay';
        loaderOverlay.innerHTML = '<div class="container-loader-spinner"></div>';
        container.appendChild(loaderOverlay);

    } else {
        const globalLoader = document.getElementById('ajax-loader');
        if (globalLoader) globalLoader.style.display = 'flex';
    }
}

/**
 * Cache un loader.
 * @param {string|null} targetSelector - Le sélecteur du conteneur. Si null, cache le loader global.
 */
function hideLoader(targetSelector = null) {
    if (targetSelector) {
        const container = document.querySelector(targetSelector);
        if (container) {
            const loaderOverlay = container.querySelector('.container-loader-overlay');
            if (loaderOverlay) {
                loaderOverlay.remove();
            }
        }
    } else {
        const globalLoader = document.getElementById('ajax-loader');
        if (globalLoader) globalLoader.style.display = 'none';
    }
}


function hideProgressLoader(blurredElement) {
    const progressLoader = document.getElementById('ajax-progress-loader');
    const progressBar = document.getElementById('ajax-progress-bar');

    if (progressLoader && progressBar) {
        gsap.to(progressLoader, {
            opacity: 0,
            duration: 0.4,
            ease: 'power2.in',
            onComplete: () => {
                progressLoader.style.display = 'none';
                progressLoader.style.opacity = '1';
                progressBar.style.width = '0%';
            }
        });
    }
    if (blurredElement) {
        blurredElement.classList.remove('ajax-content-loading');
    }
}

// -------------------------------------------------------------------
// Gestion de la navigation et des mises à jour de contenu
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

    const progressLoader = document.getElementById('ajax-progress-loader');
    const progressBar = document.getElementById('ajax-progress-bar');
    const contentWrapper = document.querySelector('.main-content') || document.querySelector('.main-content-wrapper') || document.querySelector(target) || document.body;

    if (progressLoader && progressBar && contentWrapper) {
        contentWrapper.classList.add('ajax-content-loading');
        progressLoader.style.display = 'block';
        gsap.set(progressBar, {width: '0%', opacity: 1});
        gsap.to(progressBar, {
            width: '85%',
            duration: 2,
            ease: 'power3.out'
        });
    }

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
        let data = contentType?.includes('application/json') ? await response.json() : {html: await response.text()};

        await handleNavigationResponse(data, url, target, contentWrapper);

    } catch (error) {
        console.error('Erreur de navigation AJAX:', error);
        showPopup('Erreur lors du chargement de la page', 'error');
        hideProgressLoader(contentWrapper);
    }
}

async function handleNavigationResponse(data, url, target, blurredElement) {
    const container = document.querySelector(target);
    const progressBar = document.getElementById('ajax-progress-bar');

    if (progressBar) {
        await gsap.to(progressBar, {
            width: '100%',
            duration: 0.3,
            ease: 'power2.in'
        }).then();
    }

    hideProgressLoader(blurredElement);

    if (!container) {
        showPopup('Conteneur cible introuvable', 'error');
        return;
    }

    if (data.html) {
        let content = data.html;

        if (isFullPage(content)) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const mainContent = tempDiv.querySelector('.main-content') || tempDiv.querySelector('.main-content-wrapper') || tempDiv.querySelector(target);
            content = mainContent ? mainContent.innerHTML : content;
        }

        container.innerHTML = content;

        container.classList.add('content-entering');

        if (url !== window.location.href) {
            window.history.pushState({url, target}, '', url);
        }

        rebindEventsInContainer(container);

        setTimeout(() => {
            container.classList.remove('content-entering');
        }, 500);
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
    if (activeLink) {
        activeLink.classList.add('active');
    }
}

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
    executeRebinders();
    reloadContainerScripts(container);
}

function reloadContainerScripts(container) {
    container.querySelectorAll('script').forEach(script => {
        if (script.textContent.trim() && !script.src) {
            try {
                new Function(script.textContent)();
            } catch (error) {
                console.warn('Erreur lors de l\'exécution du script:', error);
            }
        }
    });
}

// -------------------------------------------------------------------
// Gestion de la soumission de formulaire
// -------------------------------------------------------------------

function bindAjaxForms() {
    document.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();

            // Vérifier si le formulaire a un attribut warning
            const warningMessage = form.dataset.warning || form.getAttribute('warning');
            if (warningMessage && !form.dataset.warningConfirmed) {
                // Afficher la card de warning
                showWarningCard(warningMessage, () => {
                    // Si l'utilisateur confirme, marquer comme confirmé et soumettre
                    form.dataset.warningConfirmed = 'true';
                    submitAjaxForm(form);
                });
                return;
            }

            await submitAjaxForm(this);
        });
    });
}


let isSubmitting = false;

async function submitAjaxForm(form) {
    if (isSubmitting) {
        return;
    }

    isSubmitting = true;
    const submitButton = form.querySelector('button[type="submit"]');
    const target = form.dataset.target;

    if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    }

    if (target) {
        showLoader(target);
    } else if (target === 'global') {
        showLoader();
    }

    try {
        const response = await fetch(form.action || window.location.href, {
            method: form.method || 'POST',
            body: new FormData(form),
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html'
            }
        });
        await handleFormResponse(response, form);
    } catch (error) {
        showPopup('Erreur réseau', 'error');
        console.error('Erreur AJAX:', error);
    } finally {
        isSubmitting = false;
        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }
        hideLoader(target);
        hideLoader();
    }
}

async function handleFormResponse(response, form) {
    const contentType = response.headers.get('content-type');
    let data;

    if (!response.ok) {
        showPopup(`Erreur serveur (${response.status})`, 'error');
        return;
    }

    if (contentType?.includes('application/json')) {
        data = await response.json();
    } else {
        data = {html: await response.text()};
    }

    if (data.redirect) {
        showPopup(data.message || 'Redirection...', data.statut || 'info');
        setTimeout(() => {
            window.location.href = data.redirect;
        }, 500);
        return; // Stop further processing if redirect is present
    }

    if (data.statut) {
        const messageType = data.statut === 'succes' || data.statut === 'success' ? 'success' : data.statut;
        showPopup(data.message || 'Opération réussie', messageType);
    }

    if (data.html) {
        const targetSelector = form.dataset.target || ".form-content";
        const targetContainer = document.querySelector(targetSelector);

        if (targetContainer) {
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = data.html;
            const newContentSource = tempDiv.querySelector(targetSelector);

            if (newContentSource) {
                targetContainer.innerHTML = newContentSource.innerHTML;
                rebindEventsInContainer(targetContainer);
            } else {
                targetContainer.innerHTML = data.html;
                rebindEventsInContainer(targetContainer);
            }
        } else {
            console.error(`Le conteneur cible '${targetSelector}' n'a pas été trouvé.`);
        }
    }
}

// -------------------------------------------------------------------
// Fonctions utilitaires
// -------------------------------------------------------------------

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
            window.history.pushState({}, '', url);
        }
        rebindEventsInContainer(container);
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
            const linkLikeObject = {
                href: e.state.url,
                dataset: {target: e.state.target || '#content-area'}
            };
            await handleAjaxNavigation(linkLikeObject);
        }
    });
}

function showWarningCard(message, onConfirm) {
    const warningCard = document.getElementById('warning-card');
    const warningMessage = document.getElementById('warning-message');
    const continueBtn = document.getElementById('warning-continue');
    const cancelBtn = document.getElementById('warning-cancel');

    if (!warningCard || !warningMessage) return;

    warningMessage.textContent = message;
    warningCard.style.display = 'flex';

    continueBtn.onclick = () => {
        hideWarningCard();
        if (onConfirm) onConfirm();
    };

    cancelBtn.onclick = () => hideWarningCard();

    warningCard.onclick = (event) => {
        if (event.target === warningCard) {
            hideWarningCard();
        }
    };
}


function hideWarningCard() {
    const warningCard = document.getElementById('warning-card');
    if (warningCard) {
        warningCard.style.display = 'none';
    }
}

window.ajaxRequest = async function (url, options = {}) {
    const method = options.method || 'GET';
    const shouldShowLoader = options.showLoader !== false;

    if (shouldShowLoader) {
        showLoader();
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
            hideLoader();
        }
    }
};

// Export des fonctions
window.showPopup = showPopup;
window.closePopup = closePopup;
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.showWarningCard = showWarningCard;
window.hideWarningCard = hideWarningCard;
