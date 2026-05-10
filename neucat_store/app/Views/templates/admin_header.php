
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title']; ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css">
    <style>
        .admin-topbar {
            background: linear-gradient(90deg, #0f0f0f 0%, #1a1500 100%);
            border-bottom: 2px solid rgba(212,175,55,0.3);
            padding: 0 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 200;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
        }
        .admin-topbar-brand {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .admin-topbar-brand img {
            height: 40px;
            width: auto;
            object-fit: contain;
            border-radius: 4px;
        }
        .admin-topbar-brand .admin-label {
            background: rgba(212,175,55,0.15);
            border: 1px solid rgba(212,175,55,0.35);
            color: var(--gold);
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 3px 10px;
            border-radius: 4px;
        }
        .admin-topbar-nav {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .admin-topbar-nav a {
            color: var(--gray);
            font-size: 13px;
            font-weight: 600;
            padding: 7px 16px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
        }
        .admin-topbar-nav a:hover {
            color: var(--gold);
            border-color: rgba(212,175,55,0.2);
            background: rgba(212,175,55,0.06);
        }
        .admin-topbar-nav a.active {
            color: var(--gold);
            border-color: rgba(212,175,55,0.3);
            background: rgba(212,175,55,0.08);
        }
        .admin-topbar-right {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .admin-user-chip {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 5px 14px 5px 10px;
            font-size: 13px;
            color: var(--gray);
        }
        .admin-logout-btn {
            background: rgba(211,47,47,0.12);
            border: 1px solid rgba(211,47,47,0.3);
            color: #ff6b6b;
            padding: 6px 16px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
        }
        .admin-logout-btn:hover {
            background: rgba(211,47,47,0.25);
            color: #ff6b6b;
        }
        .admin-view-store {
            color: var(--gray);
            font-size: 12px;
            padding: 6px 14px;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 4px;
            transition: all 0.2s ease;
        }
        .admin-view-store:hover {
            color: var(--white);
            border-color: rgba(255,255,255,0.2);
        }
    </style>
</head>
<body>
    <header class="admin-topbar">
        <div class="admin-topbar-brand">
            <img src="<?php echo URLROOT; ?>/img/logo.jpg" alt="Neucat"
                 onerror="this.style.display='none';">
            <span class="admin-label">Admin Panel</span>
        </div>

        <nav class="admin-topbar-nav">
            <a href="<?php echo URLROOT; ?>/admin"
               class="<?php echo (strpos($_SERVER['REQUEST_URI'], '/admin/add') === false && strpos($_SERVER['REQUEST_URI'], '/admin/edit') === false) ? 'active' : ''; ?>">
               📦 Produtos
            </a>
            <a href="<?php echo URLROOT; ?>/admin/add">+ Novo Produto</a>
        </nav>

        <div class="admin-topbar-right">
            <span class="admin-user-chip">
                🔑 <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?>
            </span>
            <a href="<?php echo URLROOT; ?>" class="admin-view-store">🛒 Ver Loja</a>
            <a href="<?php echo URLROOT; ?>/auth/logout" class="admin-logout-btn">Sair</a>
        </div>
    </header>
