/**
 * Système AJAX avancé - Support des vues complètes et fragments
 * - Gestion des réponses JSON et HTML
 * - Mise à jour du DOM et de l'URL
 * - Navigation AJAX avec liens
 * - Compatible avec l'ancien code et le CSS existant
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation
    createGlobalElements();
    bindAjaxForms();
    bindAjaxLinks();
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

    // Warning Card - Nouvelle card de confirmation
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
// Fonctions pour la Warning Card
// -------------------------------------------------------------------
function showWarningCard(message, onConfirm) {
    const warningCard = document.getElementById('warning-card');
    const warningMessage = document.getElementById('warning-message');
    const continueBtn = document.getElementById('warning-continue');
    const cancelBtn = document.getElementById('warning-cancel');

    if (!warningCard || !warningMessage) return;

    // Définir le message
    warningMessage.textContent = message;

    // Afficher la card
    warningCard.style.display = 'flex';

    // Gestionnaire pour le bouton Continuer
    const handleContinue = () => {
        hideWarningCard();
        if (onConfirm) onConfirm();
        cleanup();
    };

    // Gestionnaire pour le bouton Annuler
    const handleCancel = () => {
        hideWarningCard();
        cleanup();
    };

    // Gestionnaire pour fermer en cliquant sur l'overlay
    const handleOverlayClick = (e) => {
        if (e.target === warningCard) {
            handleCancel();
        }
    };

    // Nettoyer les anciens event listeners
    const cleanup = () => {
        continueBtn.removeEventListener('click', handleContinue);
        cancelBtn.removeEventListener('click', handleCancel);
        warningCard.removeEventListener('click', handleOverlayClick);
    };

    // Ajouter les event listeners
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
// Gestion des formulaires AJAX
// -------------------------------------------------------------------
function bindAjaxForms() {
    document.querySelectorAll('form.ajax-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
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

        await handleResponse(response, { element: form, url: action });

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

// -------------------------------------------------------------------
// Gestion des liens AJAX
// -------------------------------------------------------------------
function bindAjaxLinks() {
    document.querySelectorAll('a.nav-link-ajax, a.ajax-link').forEach(link => {
        link.addEventListener('click', async function(e) {
            e.preventDefault();

            const url = this.href;
            const target = this.dataset.target || '#main-container';

            // Gestion de l'état actif du menu
            updateActiveNavItem(this);

            await loadAjaxContent(url, target, this);
        });
    });
}

async function loadAjaxContent(url, target, element) {
    showLoader();

    try {
        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json, text/html'
            }
        });

        await handleResponse(response, { element, url, target });

    } catch (error) {
        showPopup('Erreur de chargement', 'error');
        console.error('Erreur AJAX:', error);
    } finally {
        hideLoader();
    }
}

function updateActiveNavItem(clickedLink) {
    // Retirer la classe active de tous les liens de navigation
    document.querySelectorAll('.nav-link-ajax, .ajax-link').forEach(link => {
        link.classList.remove('active');
        // Si le lien est dans un li, retirer aussi la classe du parent
        const parentLi = link.closest('li');
        if (parentLi) {
            parentLi.classList.remove('active');
        }
    });

    // Ajouter la classe active au lien cliqué
    clickedLink.classList.add('active');
    const parentLi = clickedLink.closest('li');
    if (parentLi) {
        parentLi.classList.add('active');
    }
}

// -------------------------------------------------------------------
// Gestion unifiée des réponses
// -------------------------------------------------------------------
async function handleResponse(response, context) {
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
        return;
    }

    // Si on a du HTML (vue), le traiter en premier
    if (data.html) {
        if (isFullPage(data.html)) {
            replaceFullPage(data.html, context.url); // Vue complète
        } else {
            updateContent(data.html, context); // Fragment HTML
        }
    }

    // Ensuite traiter les messages/statuts JSON (après la mise à jour de la vue)
    if (data.statut === 'succes' || data.statut === 'success') {
        const message = data.message || (context.element?.tagName === 'FORM' ? 'Opération réussie' : 'Contenu chargé');
        showPopup(message, 'success');

        // Callback personnalisé pour les formulaires
        if (context.element?.tagName === 'FORM') {
            const callback = context.element.dataset.callback;
            if (callback && window[callback]) {
                window[callback](data);
            }

            // Réinitialisation du formulaire
            if (context.element.dataset.reset !== 'false') {
                context.element.reset();
            }
        }

        // Si une redirection est demandée (avec délai)
        if (data.redirect) {
            setTimeout(() => {
                window.location.href = data.redirect;
            }, data.redirectDelay || 2000);
        }
    }
    else if (data.statut === 'error') {
        showPopup(data.message || 'Une erreur est survenue', 'error');
    }
    else if (data.statut === 'warning') {
        showPopup(data.message);
    }
    else if (data.statut === 'info') {
        showPopup(data.message || 'Information', 'info');
    }
    // Si on a seulement du HTML sans statut, pas de popup supplémentaire pour les liens
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
    bindAjaxLinks();

    // Ré-exécuter les scripts
    reloadScripts();
}

function updateContent(html, context) {
    const target = context.target || context.element?.dataset?.target || '#main-container';
    const container = document.querySelector(target);

    if (container) {
        container.innerHTML = html;

        // Mettre à jour l'URL si c'est un lien de navigation
        if (context.url && context.url !== window.location.href) {
            window.history.pushState({}, '', context.url);
        }

        // Rebind les nouveaux formulaires et liens AJAX dans le contenu mis à jour
        container.querySelectorAll('form.ajax-form').forEach(newForm => {
            newForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                await submitAjaxForm(this);
            });
        });

        container.querySelectorAll('a.nav-link-ajax, a.ajax-link').forEach(newLink => {
            newLink.addEventListener('click', async function(e) {
                e.preventDefault();
                const url = this.href;
                const target = this.dataset.target || '#main-container';
                updateActiveNavItem(this);
                await loadAjaxContent(url, target, this);
            });
        });

        // Message de succès seulement pour les formulaires
        if (context.element?.tagName === 'FORM') {
            showPopup('Contenu mis à jour', 'success');
        }
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

        // Gestion des réponses JSON avec statut
        if (typeof data === 'object' && data !== null) {
            if (data.statut === 'succes') {
                if (options.success) options.success(data);
                else showPopup(data.message || 'Opération réussie', 'success');
            } else if (data.statut === 'error') {
                if (options.error) options.error(data);
                else showPopup(data.message || 'Une erreur est survenue', 'error');
            } else if (data.statut === 'warning') {
                showPopup(data.message || 'Avertissement', 'warning');
                if (options.success) options.success(data);
            } else if (data.statut === 'info') {
                showPopup(data.message || 'Information', 'info');
                if (options.success) options.success(data);
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
        if (shouldShowLoader) hideLoader();
    }
};

// Fonction utilitaire pour charger du contenu AJAX programmatiquement
window.loadAjaxContent = loadAjaxContent;

window.showPopup = showPopup;
window.closePopup = closePopup;
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.showWarningCard = showWarningCard;
window.hideWarningCard = hideWarningCard;