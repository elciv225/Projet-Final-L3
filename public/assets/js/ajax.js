/**
 * Système AJAX avancé - Support des vues complètes, fragments ET navigation
 * - Gestion des réponses JSON et HTML
 * - Mise à jour du DOM et de l'URL
 * - Navigation dynamique avec liens AJAX
 * - Loader spécifique aux conteneurs
 * - Compatible avec l'ancien code et le CSS existant
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialisation
    createGlobalElements();
    bindAjaxForms();
    bindAjaxLinks(); // Nouvelle fonction pour les liens
    bindHistoryEvents();
});

// -------------------------------------------------------------------
// Fonctions existantes (conservées)
// -------------------------------------------------------------------
function createGlobalElements() {
    // Popup - Structure corrigée pour correspondre au CSS
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
        document.body.appendChild(popup);

        popup.querySelector('.close-popup').addEventListener('click', closePopup);
    }

    // Loader global - Structure corrigée
    if (!document.getElementById('ajax-loader')) {
        const loader = document.createElement('div');
        loader.id = 'ajax-loader';
        loader.className = 'loader-overlay';
        loader.style.display = 'none';
        loader.innerHTML = '<div class="loader"></div>';
        document.body.appendChild(loader);
    }

    // Warning Card - Card de confirmation
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
        document.body.appendChild(warningCard);
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
// NOUVELLES FONCTIONS - Gestion des loaders spécifiques aux conteneurs
// -------------------------------------------------------------------
function showContainerLoader(containerId) {
    const container = document.querySelector(containerId);
    if (!container) return;

    // Supprimer le loader existant s'il y en a un
    const existingLoader = container.querySelector('.container-loader');
    if (existingLoader) {
        existingLoader.remove();
    }

    // Créer et ajouter le nouveau loader
    const loader = document.createElement('div');
    loader.className = 'container-loader';
    loader.innerHTML = `
        <div class="container-loader-overlay">
            <div class="container-loader-spinner"></div>
            <p>Chargement...</p>
        </div>
    `;

    container.style.position = 'relative';
    container.appendChild(loader);
}

function hideContainerLoader(containerId) {
    const container = document.querySelector(containerId);
    if (!container) return;

    const loader = container.querySelector('.container-loader');
    if (loader) {
        loader.remove();
    }
}

// -------------------------------------------------------------------
// NOUVELLES FONCTIONS - Navigation AJAX
// -------------------------------------------------------------------
function bindAjaxLinks() {
    // Gestion des liens avec la classe nav-link-ajax
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

    // Mettre à jour l'état actif du menu
    updateActiveNavigation(link);

    // Afficher le loader dans le conteneur cible
    showContainerLoader(target);

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

        // Mettre à jour le contenu
        await handleNavigationResponse(data, url, target);

    } catch (error) {
        console.error('Erreur de navigation AJAX:', error);
        showPopup('Erreur lors du chargement de la page', 'error');
    } finally {
        hideContainerLoader(target);
    }
}

async function handleNavigationResponse(data, url, target) {
    const container = document.querySelector(target);


    if (!container) {
        showPopup('Conteneur cible introuvable', 'error');
        return;
    }

    // Si on a du HTML
    if (data.html) {
        // Extraire le contenu si c'est une page complète
        let content = data.html;

        if (isFullPage(content)) {
            // Extraire le contenu du main-content
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = content;
            const mainContent = tempDiv.querySelector('.main-content');
            content = mainContent ? mainContent.outerHTML : content;
        }

        // Mettre à jour le contenu du conteneur
        container.innerHTML = content;

        // Mettre à jour l'URL sans recharger la page
        if (url !== window.location.href) {
            window.history.pushState({url, target}, '', url);
        }

        // Rebinder les événements sur le nouveau contenu
        rebindEventsInContainer(container);

    }

    // Traiter les messages JSON si présents
    if (data.statut) {
        const messageType = data.statut === 'succes' ? 'success' : data.statut;
        showPopup(data.message || 'Action terminée', messageType);
    }
}

function updateActiveNavigation(activeLink) {
    // Retirer la classe active de tous les liens
    document.querySelectorAll('.nav-link-ajax').forEach(link => {
        link.classList.remove('active');
    });

    // Ajouter la classe active au lien cliqué
    activeLink.classList.add('active');
}

function rebindEventsInContainer(container) {
    // Rebinder les formulaires AJAX dans le nouveau contenu
    container.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            await submitAjaxForm(this);
        });
    });

    // Rebinder les liens AJAX dans le nouveau contenu
    container.querySelectorAll('.nav-link-ajax').forEach(link => {
        link.addEventListener('click', async function (e) {
            e.preventDefault();
            await handleAjaxNavigation(this);
        });
    });

    // Rebinder les scripts spécifiques (comme personnel-administratif.js)
    reloadContainerScripts(container);
}

function reloadContainerScripts(container) {
    // Exécuter les scripts inline dans le nouveau contenu
    container.querySelectorAll('script').forEach(script => {
        if (script.textContent.trim()) {
            try {
                eval(script.textContent);
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

    // Animation du bouton de soumission
    if (submitButton) {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
    }

    // Afficher le loader approprié
    if (target) {
        showContainerLoader(target);
    } else {
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
        // Masquer le loader approprié
        if (target) {
            hideContainerLoader(target);
        } else {
            hideLoader();
        }

        // Restauration du bouton
        if (submitButton) {
            submitButton.classList.remove('loading');
            submitButton.disabled = false;
        }

        // Supprimer la confirmation de warning après soumission
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
            showContainerLoader(target);

            try {
                const response = await fetch(e.state.url, {
                    headers: {'X-Requested-With': 'XMLHttpRequest'}
                });
                const html = await response.text();
                await handleNavigationResponse({html}, e.state.url, target);

                // Mettre à jour le menu actif
                const activeLink = document.querySelector(`[href="${e.state.url}"]`);
                if (activeLink) {
                    updateActiveNavigation(activeLink);
                }

            } catch (error) {
                console.error('Erreur de navigation historique:', error);
                showPopup('Erreur de navigation', 'error');
            } finally {
                hideContainerLoader(target);
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
            showContainerLoader(target);
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
                hideContainerLoader(target);
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
window.showContainerLoader = showContainerLoader;
window.hideContainerLoader = hideContainerLoader;
window.showWarningCard = showWarningCard;
window.hideWarningCard = hideWarningCard;