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
        .auth-page {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            background: radial-gradient(ellipse at center, #1a1500 0%, #0a0a0a 70%);
            padding: 20px;
        }
        .auth-card {
            background: rgba(21,21,21,0.97); border: 1px solid rgba(212,175,55,0.25);
            border-radius: 14px; padding: 50px 40px; width: 100%; max-width: 420px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
        }
        .auth-logo { text-align: center; margin-bottom: 28px; }
        .auth-logo img { height: 70px; width: auto; object-fit: contain; }
        .auth-card h2 { text-align: center; font-size: 22px; font-weight: 700; color: var(--white); margin-bottom: 6px; }
        .auth-card h2 span { color: var(--gold); }
        .auth-card p.subtitle { text-align: center; font-size: 14px; color: var(--gray); margin-bottom: 32px; }
        .form-group { margin-bottom: 20px; }
        .form-group label {
            display: block; font-size: 11px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase; color: var(--gray); margin-bottom: 8px;
        }
        .form-group input {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;
            padding: 14px 16px; color: var(--white);
            font-family: 'Outfit', sans-serif; font-size: 15px;
            outline: none; transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group input:focus { border-color: rgba(212,175,55,0.6); box-shadow: 0 0 0 3px rgba(212,175,55,0.08); }
        .form-group input::placeholder { color: rgba(136,136,136,0.6); }
        .alert-error {
            background: rgba(211,47,47,0.12); border: 1px solid rgba(211,47,47,0.35);
            border-radius: 6px; padding: 12px 16px; font-size: 14px;
            color: #ff6b6b; margin-bottom: 22px; text-align: center;
        }
        .btn-auth {
            width: 100%; padding: 15px;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: var(--black); border: none; border-radius: 6px;
            font-family: 'Outfit', sans-serif; font-size: 15px;
            font-weight: 700; letter-spacing: 1.5px; text-transform: uppercase;
            cursor: pointer; transition: all 0.3s ease; margin-top: 8px;
        }
        .btn-auth:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(212,175,55,0.3); }
        .auth-footer { text-align: center; margin-top: 28px; font-size: 14px; color: var(--gray); line-height: 2; }
        .auth-footer a { color: var(--gold); font-weight: 600; }
        .hint {
            font-size: 13px; color: var(--gray); margin-bottom: 24px; text-align: center;
            padding: 10px; background: rgba(212,175,55,0.06); border-radius: 6px;
        }
    </style>
</head>
<body>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <img src="<?php echo URLROOT; ?>/img/logo.jpg" alt="Neucat Logo"
                 onerror="this.style.display='none'; document.querySelector('.auth-brand-text').style.display='block';">
            <span class="auth-brand-text" style="display:none; font-size:32px; font-weight:800; color:var(--gold); letter-spacing:4px;">NEUCAT</span>
        </div>
        <h2>Bem-vindo <span>NeuCustomer</span></h2>
        <p class="subtitle">Acesse sua conta para ver preços exclusivos</p>

        <div class="hint">💰 Clientes têm acesso a preços promocionais especiais</div>

        <?php if (!empty($data['error'])): ?>
            <div class="alert-error">⚠ <?php echo $data['error']; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/neucliente/login">
            <div class="form-group">
                <label for="login-id">E-mail ou Telefone</label>
                <input type="text" id="login-id" name="login_id"
                       value="<?php echo $data['login_id']; ?>"
                       placeholder="seu@email.com ou +244..." autocomplete="username" required>
            </div>
            <div class="form-group">
                <label for="login-pass">Senha</label>
                <input type="password" id="login-pass" name="password"
                       placeholder="••••••••" autocomplete="current-password" required>
            </div>
            <button type="submit" class="btn-auth">Entrar</button>
        </form>

        <div class="auth-footer">
            Ainda não tem conta? <a href="<?php echo URLROOT; ?>/neucliente/register">Cadastre-se grátis</a><br>
            <a href="<?php echo URLROOT; ?>">← Voltar à loja</a>
        </div>
    </div>
</div>
</body>
</html>
