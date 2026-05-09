<?php require APPROOT . '/Views/templates/admin_header.php'; ?>

<main class="container" style="padding-top:40px; padding-bottom:80px; min-height:70vh;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px;">
        <h1 class="section-title" style="margin-bottom:0;">Gerenciar <span class="text-gold">Categorias</span></h1>
        <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-gold" style="padding:8px 18px;">Voltar</a>
    </div>

    <div style="background-color:var(--black-light); padding:20px; border-radius:4px; margin-bottom:40px;">
        <form action="<?php echo URLROOT; ?>/admin/addCategory" method="POST" style="display:flex; gap:10px;">
            <input type="text" name="name" placeholder="Nova Categoria" required style="flex:1; padding:10px; background:var(--black); border:1px solid var(--gold); color:white; border-radius:4px;">
            <button type="submit" class="btn btn-gold">Adicionar</button>
        </form>
    </div>

    <table style="width:100%; border-collapse:collapse; text-align:left; background-color:var(--black-light); border-radius:4px; overflow:hidden;">
        <thead>
            <tr style="background-color:rgba(212,175,55,0.1); border-bottom:1px solid rgba(212,175,55,0.3);">
                <th style="padding:15px; color:var(--gold);">ID</th>
                <th style="padding:15px; color:var(--gold);">Nome da Categoria</th>
                <th style="padding:15px; color:var(--gold); width:200px;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($data['categories'] as $category): ?>
                <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                    <td style="padding:15px; color:var(--gray);"><?php echo $category->id; ?></td>
                    <td style="padding:15px;">
                        <form action="<?php echo URLROOT; ?>/admin/editCategory/<?php echo $category->id; ?>" method="POST" style="display:flex; gap:10px; margin:0;">
                            <input type="text" name="name" value="<?php echo htmlspecialchars($category->name); ?>" required style="flex:1; padding:8px; background:var(--black); border:1px solid var(--gray); color:white; border-radius:4px;">
                            <button type="submit" class="btn btn-outline-gold" style="padding:8px 16px;">Salvar</button>
                        </form>
                    </td>
                    <td style="padding:15px;">
                        <form action="<?php echo URLROOT; ?>/admin/deleteCategory/<?php echo $category->id; ?>" method="POST" style="margin:0;" onsubmit="return confirm('Deletar categoria?');">
                            <button type="submit" class="btn" style="padding:8px 16px; background:#d32f2f; color:white; border:none; border-radius:4px;">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            <?php if(empty($data['categories'])): ?>
                <tr><td colspan="3" style="padding:30px; text-align:center; color:var(--gray);">Nenhuma categoria cadastrada.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</main>

<?php require APPROOT . '/Views/templates/admin_footer.php'; ?>
