
<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="Neucat - Loja virtual de produtos premium com design exclusivo.">
    <title><?php echo $data['title'] ?? 'Neucat'; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <style>
        /* Light mode overrides */
        [data-theme="light"] {
            --black: #ffffff;
            --black-light: #f5f5f5;
            --white: #111111;
            background-color: var(--black);
            color: var(--white);
        }
        [data-theme="light"] .navbar {
            background-color: rgba(255, 255, 255, 0.95);
            border-bottom: 1px solid #ddd;
        }
        [data-theme="light"] .product-card {
            background: #fff;
            border: 1px solid #ddd;
        }
        [data-theme="light"] .footer {
            background: #f9f9f9;
            border-top: 1px solid #eee;
            color: #333;
        }

        .search-container { position: relative; margin-left: 20px; flex: 1; max-width: 300px; }
        .search-input { padding: 8px 12px; border-radius: 20px; border: 1px solid var(--gold); background: transparent; color: inherit; width: 100%; }
        .autocomplete-results { position: absolute; top: 100%; left: 0; right: 0; background: var(--black-light); border: 1px solid var(--gold); border-radius: 4px; display: none; z-index: 1000; max-height: 300px; overflow-y: auto; }
        .autocomplete-item { padding: 10px; display: flex; align-items: center; gap: 10px; cursor: pointer; border-bottom: 1px solid rgba(255,255,255,0.1); color: inherit; text-decoration: none; }
        .autocomplete-item:hover { background: rgba(212,175,55,0.1); }
        .autocomplete-item img { width: 30px; height: 30px; object-fit: cover; border-radius: 4px; }
        
        .theme-toggle { background: none; border: none; font-size: 20px; cursor: pointer; color: inherit; margin-left: 15px; }
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; background-color: var(--black-light); min-width: 160px; box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2); z-index: 100; border: 1px solid var(--gold); border-radius: 4px; right: 0; }
        .dropdown-content a { color: inherit; padding: 12px 16px; text-decoration: none; display: block; }
        .dropdown-content a:hover { background-color: rgba(212,175,55,0.1); }
        .dropdown:hover .dropdown-content { display: block; }

        /* Mobile Menu */
        .mobile-menu-btn { display: none; font-size: 24px; background: none; border: none; color: inherit; cursor: pointer; margin-left: 15px; }
        .mobile-menu-overlay { display: none; position: fixed; top: 80px; left: 0; right: 0; bottom: 0; background: var(--black); z-index: 99; padding: 20px; flex-direction: column; gap: 20px; }
        .mobile-menu-overlay.active { display: flex; }
        .mobile-menu-overlay a { font-size: 18px; padding: 10px; border-bottom: 1px solid rgba(212,175,55,0.2); }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .mobile-menu-btn { display: block; }
            .search-container { display: none; } /* Hide in nav, show in mobile menu */
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="container nav-container">
            <a href="<?php echo URLROOT; ?>" class="brand">
                <img src="<?php echo URLROOT; ?>/img/logo.jpg" alt="Neucat" class="nav-logo"
                     onerror="this.style.display='none'; document.querySelector('.nav-logo-text').style.display='inline';">
                <span class="nav-logo-text" style="display:none;">NEUCAT</span>
            </a>
            
            <div class="search-container">
                <form action="<?php echo URLROOT; ?>" method="GET">
                    <input type="text" name="q" class="search-input" id="search-input" placeholder="Pesquisar..." autocomplete="off">
                </form>
                <div class="autocomplete-results" id="autocomplete-results"></div>
            </div>

            <nav>
                <ul class="nav-links">
                    <li><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>#products">Coleções</a></li>
                    <?php if (!empty($_SESSION['customer_logged_in'])): ?>
                        <li class="dropdown">
                            <a href="#" class="nav-customer-name">
                                👤 <?php echo htmlspecialchars($_SESSION['customer_name']); ?>
                            </a>
                            <div class="dropdown-content">
                                <a href="<?php echo URLROOT; ?>/neucliente/profile">Meu Perfil</a>
                                <a href="<?php echo URLROOT; ?>/neucliente/orders">Meus Pedidos</a>
                                <a href="<?php echo URLROOT; ?>/neucliente/favorites">Favoritos</a>
                                <a href="<?php echo URLROOT; ?>/neucliente/logout">Sair</a>
                            </div>
                        </li>
                    <?php else: ?>
                        <li><a href="<?php echo URLROOT; ?>/neucliente/login">Entrar</a></li>
                        <li><a href="<?php echo URLROOT; ?>/neucliente/register" class="nav-register-btn">Cadastrar</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
            <div class="nav-actions" style="display:flex; align-items:center;">
                <a href="#" class="btn-cart" id="open-cart-btn">🛒 (<span id="cart-count">0</span>)</a>
                <button class="theme-toggle" id="theme-toggle">☀️</button>
                <button class="mobile-menu-btn" id="mobile-menu-btn">☰</button>
            </div>
        </div>
    </header>

    <!-- Mobile Menu -->
    <div class="mobile-menu-overlay" id="mobile-menu">
        <form action="<?php echo URLROOT; ?>" method="GET" style="margin-bottom:20px;">
            <input type="text" name="q" placeholder="Pesquisar..." style="width:100%; padding:12px; border-radius:4px; border:1px solid var(--gold); background:transparent; color:inherit;">
        </form>
        <a href="<?php echo URLROOT; ?>">Home</a>
        <a href="<?php echo URLROOT; ?>#products">Coleções</a>
        <?php if (!empty($_SESSION['customer_logged_in'])): ?>
            <a href="<?php echo URLROOT; ?>/neucliente/profile">Meu Perfil</a>
            <a href="<?php echo URLROOT; ?>/neucliente/orders">Meus Pedidos</a>
            <a href="<?php echo URLROOT; ?>/neucliente/favorites">Favoritos</a>
            <a href="<?php echo URLROOT; ?>/neucliente/logout">Sair</a>
        <?php else: ?>
            <a href="<?php echo URLROOT; ?>/neucliente/login">Entrar</a>
            <a href="<?php echo URLROOT; ?>/neucliente/register">Cadastrar</a>
        <?php endif; ?>
    </div>

    <script>
        // Mobile Menu Toggle
        const mobileBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        mobileBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('active');
            mobileBtn.textContent = mobileMenu.classList.contains('active') ? '✕' : '☰';
        });

        // Theme Toggle
        const themeToggle = document.getElementById('theme-toggle');
        const currentTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', currentTheme);
        themeToggle.textContent = currentTheme === 'dark' ? '☀️' : '🌙';

        themeToggle.addEventListener('click', () => {
            let theme = document.documentElement.getAttribute('data-theme');
            if (theme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'light');
                localStorage.setItem('theme', 'light');
                themeToggle.textContent = '🌙';
            } else {
                document.documentElement.setAttribute('data-theme', 'dark');
                localStorage.setItem('theme', 'dark');
                themeToggle.textContent = '☀️';
            }
        });

        // Search Autocomplete
        const searchInput = document.getElementById('search-input');
        const autocompleteResults = document.getElementById('autocomplete-results');

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                if (query.length > 2) {
                    fetch(`<?php echo URLROOT; ?>/home/search_autocomplete?q=${encodeURIComponent(query)}`)
                        .then(res => res.json())
                        .then(data => {
                            autocompleteResults.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(item => {
                                    const a = document.createElement('a');
                                    a.href = `<?php echo URLROOT; ?>/home/product/${item.id}`;
                                    a.className = 'autocomplete-item';
                                    a.innerHTML = `<img src="${item.image}" alt=""> <div>${item.name}<br><small>R$ ${item.price}</small></div>`;
                                    autocompleteResults.appendChild(a);
                                });
                                autocompleteResults.style.display = 'block';
                            } else {
                                autocompleteResults.style.display = 'none';
                            }
                        });
                } else {
                    autocompleteResults.style.display = 'none';
                }
            });

            document.addEventListener('click', function(e) {
                if (e.target !== searchInput && e.target !== autocompleteResults) {
                    autocompleteResults.style.display = 'none';
                }
            });
        }
    </script>
