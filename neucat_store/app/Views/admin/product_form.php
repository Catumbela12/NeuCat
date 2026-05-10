<?php require APPROOT . '/Views/templates/admin_header.php'; ?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px; min-height: 70vh;">
    <div style="max-width: 600px; margin: 0 auto; background-color: var(--black-light); padding: 40px; border-radius: 4px; border: 1px solid rgba(212, 175, 55, 0.2);">
        <h1 class="section-title" style="margin-bottom: 30px; font-size: 28px; text-align: left;"><?php echo !empty($data['product']->id) ? 'Editar' : 'Adicionar'; ?> <span class="text-gold">Produto</span></h1>
        
        <form action="<?php echo URLROOT; ?>/admin/<?php echo !empty($data['product']->id) ? 'edit/'.$data['product']->id : 'add'; ?>" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 20px;">
            
            <?php if(!empty($data['product']->id)): ?>
                <input type="hidden" name="old_image" value="<?php echo $data['product']->image; ?>">
            <?php endif; ?>

            <div>
                <label for="name" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Nome do Produto *</label>
                <input type="text" id="name" name="name" value="<?php echo !empty($data['product']->name) ? htmlspecialchars($data['product']->name) : ''; ?>" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="category_id" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Categoria</label>
                    <select id="category_id" name="category_id" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                        <option value="">Selecione...</option>
                        <?php foreach($data['categories'] as $cat): ?>
                            <option value="<?php echo $cat->id; ?>" <?php echo (!empty($data['product']->category_id) && $data['product']->category_id == $cat->id) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($cat->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div style="flex: 1;">
                    <label for="stock_quantity" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Estoque *</label>
                    <input type="number" id="stock_quantity" name="stock_quantity" value="<?php echo isset($data['product']->stock_quantity) ? $data['product']->stock_quantity : '0'; ?>" required min="0" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
            </div>

            <div>
                <label for="description" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Descrição *</label>
                <textarea id="description" name="description" rows="4" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px; resize: vertical;"><?php echo !empty($data['product']->description) ? htmlspecialchars($data['product']->description) : ''; ?></textarea>
            </div>

            <div style="display: flex; gap: 20px;">
                <div style="flex: 1;">
                    <label for="price" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Preço (R$) *</label>
                    <input type="number" id="price" name="price" step="0.01" value="<?php echo !empty($data['product']->price) ? $data['product']->price : ''; ?>" required style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
                <div style="flex: 1;">
                    <label for="promotional_price" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Preço Promocional (R$)</label>
                    <input type="number" id="promotional_price" name="promotional_price" step="0.01" value="<?php echo !empty($data['product']->promotional_price) ? $data['product']->promotional_price : ''; ?>" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                </div>
            </div>

            <div>
                <label for="image" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Imagem Principal do Produto</label>
                <input type="file" id="image" name="image" accept="image/*" style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                <?php if(!empty($data['product']->image)): ?>
                    <div style="margin-top: 10px;">
                        <span style="font-size: 12px; color: var(--gray);">Imagem atual:</span><br>
                        <img src="<?php echo URLROOT; ?>/img/<?php echo $data['product']->image; ?>" alt="Atual" style="max-height: 100px; margin-top: 5px; border-radius: 4px;" onerror="this.style.display='none'">
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <label for="gallery_images" style="display: block; margin-bottom: 8px; color: var(--gold); font-weight: 600;">Imagens Adicionais (Galeria)</label>
                <input type="file" id="gallery_images" name="gallery_images[]" accept="image/*" multiple style="width: 100%; padding: 12px; background-color: var(--black); border: 1px solid rgba(255,255,255,0.1); color: white; border-radius: 4px;">
                
                <?php if(!empty($data['gallery'])): ?>
                    <div style="margin-top: 10px; display:flex; gap:10px; flex-wrap:wrap;">
                        <?php foreach($data['gallery'] as $img): ?>
                            <div style="position:relative;">
                                <img src="<?php echo URLROOT; ?>/img/<?php echo $img->image; ?>" style="height: 80px; border-radius: 4px;">
                                <a href="<?php echo URLROOT; ?>/admin/deleteImage/<?php echo $img->id; ?>/<?php echo $data['product']->id; ?>" 
                                   style="position:absolute; top:-5px; right:-5px; background:red; color:white; border-radius:50%; width:20px; height:20px; text-align:center; font-size:12px; line-height:20px; text-decoration:none;" 
                                   onclick="return confirm('Excluir imagem?');">X</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top: 20px; display: flex; gap: 15px;">
                <button type="submit" class="btn btn-gold" style="flex: 1;">Salvar Produto</button>
                <a href="<?php echo URLROOT; ?>/admin" class="btn btn-outline-gold" style="flex: 1; text-align: center;">Cancelar</a>
            </div>
        </form>
    </div>
</main>

<?php require APPROOT . '/Views/templates/admin_footer.php'; ?>
