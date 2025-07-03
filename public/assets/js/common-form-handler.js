/**
 * Module de gestion des formulaires interactifs.
 * Ce fichier centralise le code commun utilisé par plusieurs vues.
 */
(function() {
    /**
     * Gestionnaire de formulaires avec fonctionnalités de base.
     */
    const formHandler = {
        /**
         * Initialise un gestionnaire de formulaire.
         * @param {Object} options - Options de configuration
         * @returns {Object} L'instance du gestionnaire de formulaire
         */
        create: function(options = {}) {
            const formSelector = options.formSelector || 'form';
            const form = document.querySelector(formSelector);
            
            if (!form) {
                console.warn(`Formulaire non trouvé: ${formSelector}`);
                return null;
            }
            
            const instance = {
                form: form,
                idField: form.querySelector(options.idFieldSelector || '#id-utilisateur-form'),
                operationField: form.querySelector(options.operationFieldSelector || '#form-operation'),
                title: form.querySelector(options.titleSelector || '#form-title'),
                submitBtn: form.querySelector(options.submitBtnSelector || '#btn-submit-form'),
                cancelBtn: form.querySelector(options.cancelBtnSelector || '#btn-cancel-edit'),
                
                // Textes personnalisables
                addTitle: options.addTitle || 'Ajouter un nouvel élément',
                editTitle: options.editTitle || 'Modifier les informations',
                addButtonText: options.addButtonText || 'Ajouter',
                editButtonText: options.editButtonText || 'Modifier',
                
                // Fonctions de rappel
                onReset: options.onReset || null,
                onPopulate: options.onPopulate || null,
                
                // Champs spécifiques pour la génération de login
                loginInput: options.generateLogin ? form.querySelector(options.loginSelector || '#login') : null,
                nomInput: options.generateLogin ? form.querySelector(options.nomSelector || '[id$="-nom"]') : null,
                prenomInput: options.generateLogin ? form.querySelector(options.prenomSelector || '[id$="-prenom"]') : null,
                
                // Méthodes
                init: function() {
                    this.bindEvents();
                    return this;
                },
                
                bindEvents: function() {
                    if (this.cancelBtn) {
                        this.cancelBtn.addEventListener('click', () => this.reset());
                    }
                    
                    // Si on doit générer un login automatiquement
                    if (options.generateLogin && this.nomInput && this.prenomInput && this.loginInput) {
                        this.nomInput.addEventListener('input', () => this.updateLogin());
                        this.prenomInput.addEventListener('input', () => this.updateLogin());
                    }
                },
                
                updateLogin: function() {
                    if (!this.loginInput || !this.nomInput || !this.prenomInput) return;
                    const nom = this.nomInput.value.toLowerCase().replace(/[^a-z]/g, '');
                    const prenom = this.prenomInput.value.toLowerCase().charAt(0);
                    this.loginInput.value = (nom && prenom) ? prenom + nom : '';
                },
                
                populateForEdit: function(row) {
                    if (!this.form || !row) return;
                    this.reset();
                    
                    if (this.idField && row.dataset.userId) {
                        this.idField.value = row.dataset.userId;
                    }
                    
                    // Appeler la fonction de rappel personnalisée pour remplir les champs spécifiques
                    if (typeof this.onPopulate === 'function') {
                        this.onPopulate(this.form, row);
                    }
                    
                    // Mettre à jour le login si nécessaire
                    if (options.generateLogin) {
                        this.updateLogin();
                    }
                    
                    // Mettre à jour l'état du formulaire pour le mode édition
                    if (this.operationField) this.operationField.value = 'modifier';
                    if (this.title) this.title.textContent = this.editTitle;
                    if (this.submitBtn) this.submitBtn.textContent = this.editButtonText;
                    if (this.cancelBtn) this.cancelBtn.style.display = 'inline-block';
                    
                    // Faire défiler vers le haut pour voir le formulaire
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                },
                
                reset: function() {
                    this.form.reset();
                    
                    if (this.idField) this.idField.value = '';
                    if (this.operationField) this.operationField.value = 'ajouter';
                    if (this.title) this.title.textContent = this.addTitle;
                    if (this.submitBtn) this.submitBtn.textContent = this.addButtonText;
                    if (this.cancelBtn) this.cancelBtn.style.display = 'none';
                    
                    // Appeler la fonction de rappel personnalisée pour la réinitialisation
                    if (typeof this.onReset === 'function') {
                        this.onReset(this.form);
                    }
                    
                    // Mettre à jour le login si nécessaire
                    if (options.generateLogin) {
                        this.updateLogin();
                    }
                }
            };
            
            return instance.init();
        }
    };
    
    // Exporter le gestionnaire de formulaires
    window.formHandler = formHandler;
})();