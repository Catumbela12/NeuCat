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
            padding: 30px 20px;
        }
        .auth-card {
            background: rgba(21,21,21,0.97); border: 1px solid rgba(212,175,55,0.25);
            border-radius: 14px; padding: 48px 40px; width: 100%; max-width: 500px;
            box-shadow: 0 30px 80px rgba(0,0,0,0.6);
        }
        .auth-logo { text-align: center; margin-bottom: 28px; }
        .auth-logo img { height: 70px; width: auto; object-fit: contain; }
        .auth-card h2 { text-align: center; font-size: 24px; font-weight: 800; color: var(--white); margin-bottom: 6px; }
        .auth-card h2 span { color: var(--gold); }
        .auth-card p.subtitle { text-align: center; font-size: 14px; color: var(--gray); margin-bottom: 32px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 18px; }
        .form-group label {
            display: block; font-size: 11px; font-weight: 600;
            letter-spacing: 1.5px; text-transform: uppercase; color: var(--gray); margin-bottom: 7px;
        }
        .form-group input, .form-group select {
            width: 100%; background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.1); border-radius: 6px;
            padding: 13px 16px; color: var(--white);
            font-family: 'Outfit', sans-serif; font-size: 15px;
            outline: none; transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-group select option { background: #151515; }
        .form-group input:focus, .form-group select:focus {
            border-color: rgba(212,175,55,0.6); box-shadow: 0 0 0 3px rgba(212,175,55,0.08);
        }
        .form-group input::placeholder { color: rgba(136,136,136,0.6); }
        .alert-error {
            background: rgba(211,47,47,0.12); border: 1px solid rgba(211,47,47,0.35);
            border-radius: 6px; padding: 12px 16px; font-size: 14px;
            color: #ff6b6b; margin-bottom: 22px; text-align: center;
        }
        .alert-success {
            background: rgba(76,175,80,0.12); border: 1px solid rgba(76,175,80,0.35);
            border-radius: 6px; padding: 12px 16px; font-size: 14px;
            color: #81c784; margin-bottom: 22px; text-align: center;
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
        .auth-footer { text-align: center; margin-top: 26px; font-size: 14px; color: var(--gray); }
        .auth-footer a { color: var(--gold); font-weight: 600; }
        .badge-benefits { display: flex; gap: 10px; flex-wrap: wrap; justify-content: center; margin-bottom: 28px; }
        .badge-benefits span {
            background: rgba(212,175,55,0.1); border: 1px solid rgba(212,175,55,0.2);
            border-radius: 20px; padding: 4px 12px; font-size: 12px; color: var(--gold);
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
        <h2>Seja um <span>NeuCustomer</span></h2>
        <p class="subtitle">Crie sua conta e tenha acesso a preços exclusivos</p>

        <div class="badge-benefits">
            <span>💰 Preços promocionais</span>
            <span>⭐ Acesso exclusivo</span>
            <span>🎁 Ofertas especiais</span>
        </div>

        <?php if (!empty($data['error'])): ?>
            <div class="alert-error">⚠ <?php echo $data['error']; ?></div>
        <?php endif; ?>
        <?php if (!empty($data['success'])): ?>
            <div class="alert-success">✓ <?php echo $data['success']; ?></div>
        <?php endif; ?>

        <form method="POST" action="<?php echo URLROOT; ?>/neucliente/register">
            <div class="form-group">
                <label for="reg-name">Nome completo *</label>
                <input type="text" id="reg-name" name="name"
                       value="<?php echo $data['name']; ?>" placeholder="Seu nome completo" required>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="reg-email">E-mail</label>
                    <input type="email" id="reg-email" name="email"
                           value="<?php echo $data['email']; ?>" placeholder="seu@email.com">
                </div>
                <div class="form-group">
                    <label for="reg-phone">Telefone</label>
                    <input type="tel" id="reg-phone" name="phone"
                           value="<?php echo $data['phone']; ?>" placeholder="+244 9XX XXX XXX">
                </div>
            </div>
            <p style="font-size:12px; color:var(--gray); margin-top:-10px; margin-bottom:18px;">* Informe pelo menos e-mail ou telefone</p>
            <div class="form-row">
                <div class="form-group">
                    <label for="reg-birth">Data de nascimento *</label>
                    <input type="date" id="reg-birth" name="birth_date"
                           value="<?php echo $data['birth_date']; ?>" required>
                </div>
                <div class="form-group">
                    <label for="reg-gender">Sexo *</label>
                    <select id="reg-gender" name="gender" required>
                        <option value="">Selecione</option>
                        <option value="M" <?php echo $data['gender']==='M'?'selected':''; ?>>Masculino</option>
                        <option value="F" <?php echo $data['gender']==='F'?'selected':''; ?>>Feminino</option>
                        <option value="O" <?php echo $data['gender']==='O'?'selected':''; ?>>Prefiro não dizer</option>
                    </select>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="reg-password">Senha *</label>
                    <input type="password" id="reg-password" name="password"
                           placeholder="Mínimo 6 caracteres" required>
                </div>
                <div class="form-group">
                    <label for="reg-confirm">Confirmar senha *</label>
                    <input type="password" id="reg-confirm" name="confirm_password"
                           placeholder="Repita a senha" required>
                </div>
            </div>
            <button type="submit" class="btn-auth">Criar Minha Conta</button>
        </form>

        <div class="auth-footer">
            Já tem conta? <a href="<?php echo URLROOT; ?>/neucliente/login">Faça login aqui</a><br><br>
            <a href="<?php echo URLROOT; ?>">← Voltar à loja</a>
        </div>
    </div>
</div>
</body>
</html>
