<?php require APPROOT . '/Views/templates/header.php'; ?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px; min-height: 70vh;">
    <div style="max-width: 600px; margin: 0 auto; background-color: var(--black-light); padding: 40px; border-radius: 4px; border: 1px solid rgba(212, 175, 55, 0.2);">
        <h1 class="section-title" style="margin-bottom: 30px; font-size: 28px; text-align: left;">Meu <span class="text-gold">Perfil</span></h1>
        
        <?php if(!empty($data['success'])): ?>
            <div style="background:#4CAF50; color:white; padding:15px; text-align:center; margin-bottom:20px; border-radius:4px;">
                <?php echo $data['success']; ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo URLROOT; ?>/neucliente/updateProfile" method="POST" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div style="display:flex; gap:20px;">
                <div style="flex:1;">
                    <label for="name" style="display: block; margin-bottom: 8px; color: var(--gold);">Nome *</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($data['customer']->name); ?>" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
                <div style="flex:1;">
                    <label for="email" style="display: block; margin-bottom: 8px; color: var(--gray);">E-mail (Fixo)</label>
                    <input type="email" id="email" value="<?php echo htmlspecialchars($data['customer']->email); ?>" disabled style="width: 100%; padding: 12px; background-color: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); color: var(--gray); border-radius: 4px; cursor:not-allowed;">
                </div>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="phone" style="display: block; margin-bottom: 8px; color: var(--gold);">Telefone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($data['customer']->phone ?? ''); ?>" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label for="birth_date" style="display: block; margin-bottom: 8px; color: var(--gold);">Nascimento *</label>
                    <input type="date" id="birth_date" name="birth_date" value="<?php echo htmlspecialchars($data['customer']->birth_date); ?>" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; color-scheme: dark;">
                </div>
                <div style="flex: 1;">
                    <label for="gender" style="display: block; margin-bottom: 8px; color: var(--gold);">Sexo *</label>
                    <select id="gender" name="gender" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                        <option value="M" <?php echo $data['customer']->gender == 'M' ? 'selected' : ''; ?>>Masculino</option>
                        <option value="F" <?php echo $data['customer']->gender == 'F' ? 'selected' : ''; ?>>Feminino</option>
                        <option value="O" <?php echo $data['customer']->gender == 'O' ? 'selected' : ''; ?>>Outro</option>
                    </select>
                </div>
            </div>

            <h3 style="color:var(--gold); margin-top:10px; border-bottom:1px solid rgba(212,175,55,0.2); padding-bottom:10px;">Endereço de Entrega</h3>

            <div>
                <label for="address_line" style="display: block; margin-bottom: 8px; color: var(--gold);">Endereço Completo</label>
                <input type="text" id="address_line" name="address_line" value="<?php echo htmlspecialchars($data['customer']->address_line ?? ''); ?>" placeholder="Rua, Número, Bairro" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 2;">
                    <label for="city" style="display: block; margin-bottom: 8px; color: var(--gold);">Cidade</label>
                    <input type="text" id="city" name="city" value="<?php echo htmlspecialchars($data['customer']->city ?? ''); ?>" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label for="state" style="display: block; margin-bottom: 8px; color: var(--gold);">Estado/Província</label>
                    <input type="text" id="state" name="state" value="<?php echo htmlspecialchars($data['customer']->state ?? ''); ?>" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label for="zip" style="display: block; margin-bottom: 8px; color: var(--gold);">CEP/Código Postal</label>
                    <input type="text" id="zip" name="zip" value="<?php echo htmlspecialchars($data['customer']->zip ?? ''); ?>" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
            </div>

            <button type="submit" class="btn btn-gold" style="padding: 14px; font-size: 16px; margin-top: 10px;">Salvar Alterações</button>
        </form>
    </div>
</main>

<?php require APPROOT . '/Views/templates/footer.php'; ?>
