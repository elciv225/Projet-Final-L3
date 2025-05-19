/**
 * AJAX pour la gestion de tous les formulaires
 * avec gestion de popups et loader
 */

// Fonction d'initialisation à exécuter quand la page est chargée
document.addEventListener('DOMContentLoaded', function() {
    // Création des éléments globaux nécessaires
    createGlobalElements();

    // Recherche de tous les formulaires avec la classe "ajax-form"
    const ajaxForms = document.querySelectorAll('form.ajax-form');

    // Attache les gestionnaires d'événements à chaque formulaire
    ajaxForms.forEach(form => {
        initAjaxForm(form);
    });
});

/**
 * Crée les éléments globaux nécessaires pour les popups et le loader
 */
function createGlobalElements() {
    // Création du popup s'il n'existe pas déjà
    if (!document.getElementById('ajax-popup')) {
        const popupDiv = document.createElement('div');
        popupDiv.id = 'ajax-popup';
        popupDiv.className = 'popup';
        popupDiv.innerHTML = `
            <div class="popup-content">
                <span id="popup-message"></span>
                <span class="close-popup">&times;</span>
            </div>
        `;
        document.body.appendChild(popupDiv);

        // Ajouter le gestionnaire d'événement pour fermer le popup
        const closeBtn = popupDiv.querySelector('.close-popup');
        closeBtn.addEventListener('click', () => closePopup());
    }

    // Création du loader s'il n'existe pas déjà
    if (!document.getElementById('ajax-loader')) {
        const loaderDiv = document.createElement('div');
        loaderDiv.id = 'ajax-loader';
        loaderDiv.className = 'loader-overlay';
        loaderDiv.innerHTML = '<div class="loader"></div>';
        document.body.appendChild(loaderDiv);
    }
}

/**
 * Initialise un formulaire pour gérer les soumissions en AJAX
 * @param {HTMLFormElement} form - Le formulaire à initialiser
 */
function initAjaxForm(form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        // Récupère l'URL d'action du formulaire
        const actionUrl = form.getAttribute('action') || window.location.href;
        const method = form.getAttribute('method') || 'POST';

        // Collecte des données du formulaire
        const formData = new FormData(form);

        // Affichage du loader
        showLoader();

        // Option avancée : afficher un indicateur de chargement sur le bouton
        const submitButton = form.querySelector('button[type="submit"]');
        if (submitButton) submitButton.classList.add('loading');

        // Envoi de la requête AJAX
        fetch(actionUrl, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                // Vérifie si la réponse est un JSON
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                }
                throw new Error('La réponse n\'est pas au format JSON');
            })
            .then(data => {
                // Traitement de la réponse
                if (data.statut === 'succes' || data.status === 'success') {
                    // En cas de succès
                    showPopup(data.message || 'Opération réussie', 'success');

                    // Si une redirection est demandée
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, data.redirectDelay || 2000);
                    }

                    // Si une fonction callback est définie (via data-callback)
                    const callback = form.getAttribute('data-callback');
                    if (callback && window[callback] && typeof window[callback] === 'function') {
                        window[callback](data);
                    }

                    // Réinitialiser le formulaire si option activée
                    if (form.getAttribute('data-reset') !== 'false') {
                        form.reset();
                    }
                } else {
                    // En cas d'erreur côté serveur
                    showPopup(data.message || 'Une erreur est survenue', 'error');
                }
            })
            .catch(error => {
                // En cas d'erreur réseau/technique
                showPopup('Erreur de communication avec le serveur', 'error');
                console.error('Erreur AJAX:', error);
            })
            .finally(() => {
                // Masque le loader à la fin
                hideLoader();
                // Retire l'indicateur de chargement du bouton
                if (submitButton) submitButton.classList.remove('loading');
            });
    });
}

/**
 * Affiche un message popup
 * @param {string} message - Le message à afficher
 * @param {string} type - Le type de popup (success, error, info)
 */
function showPopup(message, type = 'info') {
    const popup = document.getElementById('ajax-popup');
    const messageElement = document.getElementById('popup-message');

    if (popup && messageElement) {
        messageElement.textContent = message;

        // Reset des classes et ajout de la classe de type
        popup.className = 'popup';
        popup.classList.add(type);
        popup.style.display = 'block';
        popup.style.animation = 'slideIn 0.5s ease-out';

        // Fermeture automatique après 3 secondes
        setTimeout(() => {
            closePopup();
        }, 3000);
    }
}

/**
 * Ferme le popup avec animation
 */
function closePopup() {
    const popup = document.getElementById('ajax-popup');
    if (popup) {
        popup.style.animation = 'fadeOut 0.5s forwards';
        setTimeout(() => {
            popup.style.display = 'none';
            popup.style.animation = 'slideIn 0.5s ease-out';
        }, 500);
    }
}

/**
 * Affiche le loader global
 */
function showLoader() {
    const loader = document.getElementById('ajax-loader');
    if (loader) {
        loader.style.display = 'flex';
    }
}

/**
 * Masque le loader global
 */
function hideLoader() {
    const loader = document.getElementById('ajax-loader');
    if (loader) {
        loader.style.display = 'none';
    }
}

/**
 * Fonction utilitaire pour faire des requêtes AJAX
 * Peut être utilisée en dehors des formulaires
 *
 * @param {string} url - L'URL de la requête
 * @param {Object} options - Options de la requête
 * @param {string} options.method - Méthode HTTP (GET, POST, etc.)
 * @param {Object|FormData} options.data - Données à envoyer
 * @param {Function} options.success - Fonction de rappel en cas de succès
 * @param {Function} options.error - Fonction de rappel en cas d'erreur
 * @param {boolean} options.showLoader - Afficher le loader ou non
 */
function ajaxRequest(url, options = {}) {
    const method = options.method || 'GET';
    const showLoaderIndicator = options.showLoader !== false;

    // Configuration de la requête
    let fetchOptions = {
        method: method,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    };

    // Préparation des données selon la méthode
    if (method === 'POST' || method === 'PUT' || method === 'PATCH') {
        if (options.data instanceof FormData) {
            fetchOptions.body = options.data;
        } else if (options.data) {
            fetchOptions.headers['Content-Type'] = 'application/json';
            fetchOptions.body = JSON.stringify(options.data);
        }
    } else if (options.data) {
        // Pour GET, ajouter les paramètres à l'URL
        const params = new URLSearchParams();
        Object.keys(options.data).forEach(key => {
            params.append(key, options.data[key]);
        });
        url = `${url}${url.includes('?') ? '&' : '?'}${params.toString()}`;
    }

    // Affichage du loader si nécessaire
    if (showLoaderIndicator) showLoader();

    // Envoi de la requête
    fetch(url, fetchOptions)
        .then(response => {
            // Vérification du type de réponse
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            }
            return response.text();
        })
        .then(data => {
            if (options.success && typeof options.success === 'function') {
                options.success(data);
            }
        })
        .catch(error => {
            if (options.error && typeof options.error === 'function') {
                options.error(error);
            } else {
                showPopup('Erreur de communication avec le serveur', 'error');
                console.error('Erreur AJAX:', error);
            }
        })
        .finally(() => {
            if (showLoaderIndicator) hideLoader();
        });
}



// Exposition des fonctions publiques
window.showPopup = showPopup;
window.closePopup = closePopup;
window.showLoader = showLoader;
window.hideLoader = hideLoader;
window.ajaxRequest = ajaxRequest;