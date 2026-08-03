document.addEventListener('DOMContentLoaded', function() {

    // ===== DATA =====
    const products = [
        { id: 1, name: 'تيشيرت كلاسيك', category: 'men', price: 29.99, img: 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400&h=400&fit=crop&crop=center', desc: 'قطن ناعم، مقاس عادي' },
        { id: 2, name: 'فستان صيفي', category: 'women', price: 49.99, img: 'https://images.unsplash.com/photo-1539008835657-9e8e9680c956?w=400&h=400&fit=crop&crop=center', desc: 'قماش خفيف، ألوان زاهية' },
        { id: 3, name: 'حذاء رياضي', category: 'men', price: 79.99, img: 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400&h=400&fit=crop&crop=center', desc: 'مريح ومناسب للجري' },
        { id: 4, name: 'حقيبة يد أنيقة', category: 'accessories', price: 39.99, img: 'https://images.unsplash.com/photo-1584917865442-de89df76afd3?w=400&h=400&fit=crop&crop=center', desc: 'جلد طبيعي، تصميم عصري' },
        { id: 5, name: 'تيشيرت رياضي', category: 'men', price: 24.99, img: 'https://images.unsplash.com/photo-1583744946564-b52ac1c389c8?w=400&h=400&fit=crop&crop=center', desc: 'خامات تكنولوجية، جاف سريع' },
        { id: 6, name: 'بلوزة نسائية', category: 'women', price: 34.99, img: 'https://images.unsplash.com/photo-1564257631407-4deb1f99d992?w=400&h=400&fit=crop&crop=center', desc: 'قصة أنيقة، ناعمة الملمس' },
        { id: 7, name: 'نظارات شمسية', category: 'accessories', price: 19.99, img: 'https://images.unsplash.com/photo-1511499767150-a48a237f0083?w=400&h=400&fit=crop&crop=center', desc: 'حماية من الأشعة، إطار معدني' },
        { id: 8, name: 'جينز كلاسيك', category: 'men', price: 59.99, img: 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400&h=400&fit=crop&crop=center', desc: 'جينز بقصة مستقيمة' },
        { id: 9, name: 'فستان سهرة', category: 'women', price: 89.99, img: 'https://images.unsplash.com/photo-1566174053879-31528523f8ae?w=400&h=400&fit=crop&crop=center', desc: 'مخمل فاخر، تصميم راقٍ' },
        { id: 10, name: 'ساعة يد', category: 'accessories', price: 49.99, img: 'https://images.unsplash.com/photo-1524592094714-0f0654e20314?w=400&h=400&fit=crop&crop=center', desc: 'أنالوج، جلد أصلي' }
    ];

    // ===== STATE =====
    let cart = [];
    let currentFilter = 'all';
    let toastTimeout;

    // ===== DOM REFS =====
    const grid = document.getElementById('productsGrid');
    const newGrid = document.getElementById('newGrid');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const cartToggle = document.getElementById('cartToggle');
    const cartClose = document.getElementById('cartClose');
    const cartOverlay = document.getElementById('cartOverlay');
    const cartSidebar = document.getElementById('cartSidebar');
    const cartItemsContainer = document.getElementById('cartItems');
    const cartTotalSpan = document.getElementById('cartTotal');
    const cartCountSpan = document.getElementById('cartCount');
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    const toast = document.getElementById('toast');

    // ===== RENDER PRODUCTS =====
    function renderProducts(filter = 'all') {
        const filtered = filter === 'all' ? products : products.filter(p => p.category === filter);
        if (filtered.length === 0) {
            grid.innerHTML = `<p style="text-align:center; grid-column:1/-1; color:#4a4a4a;">لا توجد منتجات في هذا التصنيف</p>`;
            return;
        }
        grid.innerHTML = filtered.map(item => `
            <div class="product-card" data-id="${item.id}">
                <img src="${item.img}" alt="${item.name}" loading="lazy" />
                <h3>${item.name}</h3>
                <div class="category">${item.category === 'men' ? 'رجالي' : item.category === 'women' ? 'نسائي' : 'إكسسوارات'}</div>
                <p class="desc">${item.desc}</p>
                <div class="price-row">
                    <span class="price">$${item.price.toFixed(2)}</span>
                    <button class="add-btn" data-id="${item.id}">أضف</button>
                </div>
            </div>
        `).join('');

        document.querySelectorAll('.add-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.stopPropagation();
                const id = parseInt(this.dataset.id);
                const product = products.find(p => p.id === id);
                if (product) addToCart(product);
            });
        });
    }

    // ===== RENDER NEW ARRIVALS (first 4 products) =====
    function renderNewArrivals() {
        const newItems = products.slice(0, 4);
        newGrid.innerHTML = newItems.map(item => `
            <div class="new-item">
                <img src="${item.img}" alt="${item.name}" loading="lazy" />
                <h4>${item.name}</h4>
                <span class="price">$${item.price.toFixed(2)}</span>
            </div>
        `).join('');
    }

    // ===== CART FUNCTIONS =====
    function addToCart(product) {
        const existing = cart.find(item => item.id === product.id);
        if (existing) {
            existing.qty += 1;
        } else {
            cart.push({ ...product, qty: 1 });
        }
        updateCartUI();
        showToast(`تم إضافة ${product.name} إلى السلة`);
    }

    function removeFromCart(id) {
        cart = cart.filter(item => item.id !== id);
        updateCartUI();
    }

    function changeQty(id, delta) {
        const item = cart.find(i => i.id === id);
        if (!item) return;
        const newQty = item.qty + delta;
        if (newQty <= 0) {
            removeFromCart(id);
        } else {
            item.qty = newQty;
            updateCartUI();
        }
    }

    function updateCartUI() {
        const totalItems = cart.reduce((sum, i) => sum + i.qty, 0);
        cartCountSpan.textContent = totalItems;

        if (cart.length === 0) {
            cartItemsContainer.innerHTML = `<p class="empty-cart">سلة فارغة، أضف قطعك المفضلة!</p>`;
            cartTotalSpan.textContent = '$0.00';
            return;
        }

        let html = '';
        let totalPrice = 0;
        cart.forEach(item => {
            const sub = item.price * item.qty;
            totalPrice += sub;
            html += `
                <div class="cart-item" data-id="${item.id}">
                    <div class="cart-item-info">
                        <h4>${item.name}</h4>
                        <span class="item-price">$${sub.toFixed(2)}</span>
                    </div>
                    <div class="cart-item-actions">
                        <button class="qty-btn" data-id="${item.id}" data-delta="-1">−</button>
                        <span>${item.qty}</span>
                        <button class="qty-btn" data-id="${item.id}" data-delta="1">+</button>
                        <button class="remove-btn" data-id="${item.id}"><i class="fas fa-trash-alt"></i></button>
                    </div>
                </div>
            `;
        });
        cartItemsContainer.innerHTML = html;
        cartTotalSpan.textContent = `$${totalPrice.toFixed(2)}`;

        document.querySelectorAll('.qty-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                const delta = parseInt(this.dataset.delta);
                changeQty(id, delta);
            });
        });
        document.querySelectorAll('.remove-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = parseInt(this.dataset.id);
                removeFromCart(id);
                showToast('تم حذف المنتج من السلة');
            });
        });
    }

    // ===== TOAST =====
    function showToast(msg) {
        toast.textContent = msg;
        toast.classList.add('show');
        clearTimeout(toastTimeout);
        toastTimeout = setTimeout(() => {
            toast.classList.remove('show');
        }, 2500);
    }

    // ===== FILTER =====
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            currentFilter = this.dataset.filter;
            renderProducts(currentFilter);
        });
    });

    // ===== CART SIDEBAR TOGGLE =====
    function openCart() {
        cartSidebar.classList.add('open');
        cartOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeCart() {
        cartSidebar.classList.remove('open');
        cartOverlay.classList.remove('open');
        document.body.style.overflow = '';
    }
    cartToggle.addEventListener('click', openCart);
    cartClose.addEventListener('click', closeCart);
    cartOverlay.addEventListener('click', closeCart);

    // ===== MOBILE MENU =====
    menuToggle.addEventListener('click', function() {
        navLinks.classList.toggle('open');
    });

    // ===== CONTACT FORM =====
    document.getElementById('contactForm')?.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('شكراً لتواصلك! سنرد عليك قريباً');
        this.reset();
    });

    // ===== NEWSLETTER =====
    document.querySelector('.footer-newsletter form')?.addEventListener('submit', function(e) {
        e.preventDefault();
        showToast('تم الاشتراك في النشرة البريدية');
        this.reset();
    });

    // ===== CHECKOUT =====
    document.querySelector('.checkout-btn')?.addEventListener('click', function() {
        if (cart.length === 0) {
            showToast('السلة فارغة، أضف بعض المنتجات أولاً');
            return;
        }
        showToast('جاري معالجة طلبك ... شكراً لك!');
        cart = [];
        updateCartUI();
        closeCart();
    });

    // ===== INIT =====
    renderProducts('all');
    renderNewArrivals();
    updateCartUI();

});
