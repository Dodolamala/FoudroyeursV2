/**
 * Script principal du site Les Foudroyeurs
 */

document.addEventListener('DOMContentLoaded', function() {
    // Navigation responsive
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const navLinks = document.querySelector('.nav-links');
    
    if (mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', function() {
            navLinks.classList.toggle('show');
            mobileMenuBtn.textContent = navLinks.classList.contains('show') ? '✕' : '☰';
        });
    }
    
    // Navbar qui change avec le scroll
    const nav = document.querySelector('nav');
    
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }
    
    // Gestion des messages toast
    function showToast(message, type = 'success') {
        console.log('Toast:', message, type); // Log pour déboguer
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => toast.classList.add('show'), 10);
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
    
    // Afficher les messages de la session
    const sessionMessage = document.querySelector('.message');
    if (sessionMessage) {
        setTimeout(() => {
            sessionMessage.style.opacity = '0';
            setTimeout(() => sessionMessage.remove(), 300);
        }, 5000);
    }
    
    // Filtrage des produits e-shop
    const categoryButtons = document.querySelectorAll('.category-btn');
    const productCards = document.querySelectorAll('.product-card');
    
    if (categoryButtons.length && productCards.length) {
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Enlever la classe active de tous les boutons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                
                // Ajouter la classe active au bouton cliqué
                this.classList.add('active');
                
                const category = this.getAttribute('data-category');
                
                // Filtrer les produits
                productCards.forEach(card => {
                    if (category === 'all' || card.getAttribute('data-category') === category) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
    }
    
    // Gestion des formulaires AJAX
    const ajaxForms = document.querySelectorAll('form[data-ajax="true"]');
    
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');
            const originalBtnText = submitBtn ? submitBtn.textContent : '';
            
            // Désactiver le bouton pendant l'envoi
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Chargement...';
            }
            
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    // Réinitialiser le formulaire si succès
                    if (this.hasAttribute('data-reset-on-success')) {
                        this.reset();
                    }
                    
                    // Rediriger si nécessaire
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                    
                    // Mettre à jour des éléments de la page si nécessaire
                    if (data.updates) {
                        for (const selector in data.updates) {
                            const element = document.querySelector(selector);
                            if (element) {
                                element.innerHTML = data.updates[selector];
                            }
                        }
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                showToast('Une erreur est survenue lors de la communication avec le serveur', 'error');
            })
            .finally(() => {
                // Réactiver le bouton
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = originalBtnText;
                }
            });
        });
    });
    
    // Gestion du panier
    const addToCartForms = document.querySelectorAll('.add-to-cart-form');
    const cartCounters = document.querySelectorAll('.cart-count');
    
    addToCartForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Vérifier que cart_action est présent
            if (!formData.get('cart_action')) {
                formData.append('cart_action', 'add');
            }
            
            // Vérifier que tous les champs requis sont présents
            if (!formData.get('product_id')) {
                console.error('Erreur: product_id manquant');
                showToast('Une erreur est survenue: identifiant produit manquant', 'error');
                return;
            }
            
            console.log('Ajout au panier:', {
                product_id: formData.get('product_id'),
                quantity: formData.get('quantity') || 1,
                option_id: formData.get('option_id') || 'aucune',
                cart_action: formData.get('cart_action')
            });
            
            fetch('includes/cart.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Statut de la réponse:', response.status);
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues du serveur:', data);
                showToast(data.message, data.success ? 'success' : 'error');
                
                // Mettre à jour le compteur du panier
                if (data.success && data.cart && cartCounters.length) {
                    cartCounters.forEach(counter => {
                        counter.textContent = data.cart.count;
                        counter.style.display = data.cart.count > 0 ? 'inline-block' : 'none';
                    });
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                showToast('Une erreur est survenue lors de la communication avec le serveur', 'error');
            });
        });
    });
    
    // Gestion du changement de quantité dans le panier
    const quantityInputs = document.querySelectorAll('.cart-quantity');
    
    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const quantity = parseInt(this.value);
            
            if (quantity < 1) {
                this.value = 1;
                return;
            }
            
            console.log('Mise à jour quantité:', {
                item_id: itemId,
                quantity: quantity
            });
            
            const formData = new FormData();
            formData.append('cart_action', 'update');
            formData.append('item_id', itemId);
            formData.append('quantity', quantity);
            
            fetch('includes/cart.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse mise à jour quantité:', data);
                
                if (data.success && data.cart) {
                    // Mettre à jour le sous-total de l'article
                    const row = this.closest('tr');
                    const subtotalCell = row.querySelector('.item-subtotal');
                    if (subtotalCell) {
                        const price = parseFloat(row.querySelector('.item-price').getAttribute('data-price'));
                        subtotalCell.textContent = formatPrice(price * quantity);
                    }
                    
                    // Mettre à jour le total du panier
                    const totalElement = document.querySelector('.cart-total-value');
                    if (totalElement) {
                        totalElement.textContent = formatPrice(data.cart.total);
                    }
                    
                    // Mettre à jour le compteur du panier
                    if (cartCounters.length) {
                        cartCounters.forEach(counter => {
                            counter.textContent = data.cart.count;
                            counter.style.display = data.cart.count > 0 ? 'inline-block' : 'none';
                        });
                    }
                } else {
                    // Restaurer l'ancienne valeur
                    this.value = this.getAttribute('data-old-value') || 1;
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                this.value = this.getAttribute('data-old-value') || 1;
                showToast('Une erreur est survenue lors de la communication avec le serveur', 'error');
            });
            
            // Sauvegarder la valeur actuelle
            this.setAttribute('data-old-value', this.value);
        });
        
        // Sauvegarder la valeur initiale
        input.setAttribute('data-old-value', input.value);
    });
    
    // Gestion des boutons de suppression d'articles
    const removeButtons = document.querySelectorAll('.remove-item');
    
    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const row = this.closest('tr');
            
            console.log('Suppression article:', {
                item_id: itemId
            });
            
            const formData = new FormData();
            formData.append('cart_action', 'remove');
            formData.append('item_id', itemId);
            
            fetch('includes/cart.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log('Réponse suppression article:', data);
                
                if (data.success) {
                    showToast(data.message, 'success');
                    
                    // Supprimer la ligne du tableau
                    if (row) {
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                    
                    // Mettre à jour le total du panier
                    const totalElement = document.querySelector('.cart-total-value');
                    if (totalElement && data.cart) {
                        totalElement.textContent = formatPrice(data.cart.total);
                    }
                    
                    // Mettre à jour le compteur du panier
                    if (cartCounters.length && data.cart) {
                        cartCounters.forEach(counter => {
                            counter.textContent = data.cart.count;
                            counter.style.display = data.cart.count > 0 ? 'inline-block' : 'none';
                        });
                    }
                    
                    // Afficher/masquer les éléments en fonction du panier vide
                    if (data.cart && data.cart.count === 0) {
                        const emptyMessage = document.querySelector('.empty-cart-message');
                        const cartContent = document.querySelector('.cart-content');
                        const cartActions = document.querySelector('.cart-actions');
                        
                        if (emptyMessage) emptyMessage.style.display = 'block';
                        if (cartContent) cartContent.style.display = 'none';
                        if (cartActions) cartActions.style.display = 'none';
                    }
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                showToast('Une erreur est survenue lors de la communication avec le serveur', 'error');
            });
        });
    });
    
    // Gestion du vidage du panier
    const clearCartButton = document.querySelector('.clear-cart');
    
    if (clearCartButton) {
        clearCartButton.addEventListener('click', function() {
            if (confirm('Êtes-vous sûr de vouloir vider votre panier ?')) {
                console.log('Vidage du panier');
                
                const formData = new FormData();
                formData.append('cart_action', 'clear');
                
                fetch('includes/cart.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Erreur réseau: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Réponse vidage panier:', data);
                    
                    if (data.success) {
                        showToast(data.message, 'success');
                        
                        // Vider le tableau du panier
                        const cartItems = document.querySelectorAll('.cart-item');
                        cartItems.forEach(item => item.remove());
                        
                        // Mettre à jour le total
                        const totalElement = document.querySelector('.cart-total-value');
                        if (totalElement) {
                            totalElement.textContent = formatPrice(0);
                        }
                        
                        // Mettre à jour le compteur du panier
                        if (cartCounters.length) {
                            cartCounters.forEach(counter => {
                                counter.textContent = '0';
                                counter.style.display = 'none';
                            });
                        }
                        
                        // Afficher/masquer les éléments en fonction du panier vide
                        const emptyMessage = document.querySelector('.empty-cart-message');
                        const cartContent = document.querySelector('.cart-content');
                        const cartActions = document.querySelector('.cart-actions');
                        
                        if (emptyMessage) emptyMessage.style.display = 'block';
                        if (cartContent) cartContent.style.display = 'none';
                        if (cartActions) cartActions.style.display = 'none';
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Erreur détaillée:', error);
                    showToast('Une erreur est survenue lors de la communication avec le serveur', 'error');
                });
            }
        });
    }
    
    // Fonction pour formater le prix
    function formatPrice(price) {
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);
    }
});