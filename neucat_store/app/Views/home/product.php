<?php require APPROOT . '/Views/templates/header.php'; ?>

<main class="container" style="padding-top: 40px; padding-bottom: 80px; min-height: 70vh;">
    <?php if(isset($_SESSION['flash_message'])): ?>
        <div style="background:#4CAF50; color:white; padding:15px; text-align:center; margin-bottom:20px; border-radius:4px;">
            <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <div style="display:flex; flex-wrap:wrap; gap:40px; margin-bottom:60px;">
        <!-- Galeria -->
        <div style="flex:1; min-width:300px;">
            <div style="margin-bottom:10px;">
                <img id="mainImage" src="<?php echo URLROOT; ?>/img/<?php echo $data['product']->image; ?>" style="width:100%; border-radius:4px; border:1px solid var(--gold);">
            </div>
            <?php if(!empty($data['gallery'])): ?>
                <div style="display:flex; gap:10px; overflow-x:auto;">
                    <img src="<?php echo URLROOT; ?>/img/<?php echo $data['product']->image; ?>" onclick="document.getElementById('mainImage').src=this.src;" style="width:80px; height:80px; object-fit:cover; cursor:pointer; border:1px solid var(--gold); border-radius:4px;">
                    <?php foreach($data['gallery'] as $img): ?>
                        <img src="<?php echo URLROOT; ?>/img/<?php echo $img->image; ?>" onclick="document.getElementById('mainImage').src=this.src;" style="width:80px; height:80px; object-fit:cover; cursor:pointer; border:1px solid var(--gold); border-radius:4px;">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Info do Produto -->
        <div style="flex:1; min-width:300px;">
            <h1 style="font-size:32px; color:var(--gold); margin-bottom:10px;"><?php echo htmlspecialchars($data['product']->name); ?></h1>
            <p style="color:var(--gray); margin-bottom:20px;">Categoria: <?php echo htmlspecialchars($data['product']->category_name ?? 'N/A'); ?> | Avaliação: <?php echo $data['avgRating']; ?>/5</p>
            
            <p style="font-size:16px; margin-bottom:30px; line-height:1.6;"><?php echo nl2br(htmlspecialchars($data['product']->description)); ?></p>

            <div style="margin-bottom:30px;">
                <?php
                    $isCustomer   = !empty($_SESSION['customer_logged_in']);
                    $hasPromo     = !empty($data['product']->promotional_price);
                    $displayPrice = ($isCustomer && $hasPromo) ? $data['product']->promotional_price : $data['product']->price;
                ?>
                
                <?php if ($isCustomer && $hasPromo): ?>
                    <p style="text-decoration:line-through; color:var(--gray); font-size:18px;">R$ <?php echo number_format($data['product']->price, 2, ',', '.'); ?></p>
                    <p style="font-size:32px; color:#4CAF50; font-weight:bold;">R$ <?php echo number_format($data['product']->promotional_price, 2, ',', '.'); ?></p>
                <?php elseif (!$isCustomer && $hasPromo): ?>
                    <p style="font-size:32px; font-weight:bold;">R$ <?php echo number_format($data['product']->price, 2, ',', '.'); ?></p>
                    <a href="<?php echo URLROOT; ?>/neucliente/login" style="color:var(--gold); font-size:14px;">🔒 Ver preço de cliente</a>
                <?php else: ?>
                    <p style="font-size:32px; font-weight:bold;">R$ <?php echo number_format($data['product']->price, 2, ',', '.'); ?></p>
                <?php endif; ?>
            </div>

            <p style="color:var(--gray); margin-bottom:20px;">Estoque: <?php echo $data['product']->stock_quantity; ?> unidades</p>

            <div style="display:flex; gap:15px; flex-wrap:wrap;">
                <?php if($data['product']->stock_quantity > 0): ?>
                    <form action="<?php echo URLROOT; ?>/home/checkout" method="POST" style="display:flex; gap:10px; margin:0;">
                        <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                        <?php if(empty($_SESSION['customer_logged_in'])): ?>
                            <input type="email" name="email" placeholder="Seu E-mail" required style="padding:12px; border-radius:4px; border:1px solid var(--gold); background:var(--black); color:white;">
                        <?php endif; ?>
                        <input type="number" name="quantity" value="1" min="1" max="<?php echo $data['product']->stock_quantity; ?>" style="width:70px; padding:12px; border-radius:4px; border:1px solid var(--gold); background:var(--black); color:white; text-align:center;">
                        <button type="submit" class="btn btn-gold" style="padding:12px 24px; font-size:16px;">Comprar Agora</button>
                    </form>
                    
                    <button class="btn btn-outline-gold add-to-cart-btn" data-id="<?php echo $data['product']->id; ?>" data-name="<?php echo htmlspecialchars($data['product']->name); ?>" data-price="<?php echo $displayPrice; ?>" data-image="<?php echo URLROOT; ?>/img/<?php echo $data['product']->image; ?>" style="padding:12px 24px;">Adicionar ao Carrinho</button>
                <?php else: ?>
                    <button class="btn" style="background:#555; color:white; cursor:not-allowed;" disabled>Esgotado</button>
                <?php endif; ?>

                <a href="<?php echo URLROOT; ?>/home/toggleFavorite/<?php echo $data['product']->id; ?>" class="btn <?php echo $data['isFavorite'] ? 'btn-gold' : 'btn-outline-gold'; ?>" style="padding:12px 20px; font-size:20px;">
                    <?php echo $data['isFavorite'] ? '❤️' : '🤍'; ?>
                </a>
            </div>
        </div>
    </div>

    <!-- Avaliações -->
    <div style="margin-top:60px;">
        <h2 style="color:var(--gold); margin-bottom:20px;">Avaliações (<?php echo count($data['reviews']); ?>)</h2>
        
        <?php if(!empty($_SESSION['customer_logged_in'])): ?>
            <div style="background:var(--black-light); padding:20px; border-radius:4px; margin-bottom:30px;">
                <h3 style="margin-bottom:15px;">Deixe sua avaliação</h3>
                <form action="<?php echo URLROOT; ?>/home/review" method="POST">
                    <input type="hidden" name="product_id" value="<?php echo $data['product']->id; ?>">
                    <div style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:5px;">Nota (1 a 5):</label>
                        <select name="rating" required style="padding:10px; border-radius:4px; background:var(--black); border:1px solid var(--gold); color:white;">
                            <option value="5">5 - Excelente</option>
                            <option value="4">4 - Muito Bom</option>
                            <option value="3">3 - Bom</option>
                            <option value="2">2 - Ruim</option>
                            <option value="1">1 - Péssimo</option>
                        </select>
                    </div>
                    <div style="margin-bottom:15px;">
                        <label style="display:block; margin-bottom:5px;">Comentário:</label>
                        <textarea name="comment" rows="3" required style="width:100%; padding:10px; border-radius:4px; background:var(--black); border:1px solid var(--gold); color:white; resize:vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-gold">Enviar Avaliação</button>
                </form>
            </div>
        <?php else: ?>
            <p style="margin-bottom:30px; color:var(--gray);"><a href="<?php echo URLROOT; ?>/neucliente/login" style="color:var(--gold);">Faça login</a> para avaliar este produto.</p>
        <?php endif; ?>

        <?php if(empty($data['reviews'])): ?>
            <p style="color:var(--gray);">Seja o primeiro a avaliar este produto!</p>
        <?php else: ?>
            <div style="display:flex; flex-direction:column; gap:20px;">
                <?php foreach($data['reviews'] as $review): ?>
                    <div style="background:var(--black-light); padding:20px; border-radius:4px; border:1px solid rgba(212,175,55,0.2);">
                        <div style="display:flex; justify-content:space-between; margin-bottom:10px;">
                            <strong><?php echo htmlspecialchars($review->customer_name); ?></strong>
                            <span style="color:var(--gold);">
                                <?php echo str_repeat('★', $review->rating) . str_repeat('☆', 5 - $review->rating); ?>
                            </span>
                        </div>
                        <p style="color:#ddd; margin-bottom:10px;"><?php echo nl2br(htmlspecialchars($review->comment)); ?></p>
                        <small style="color:var(--gray);"><?php echo date('d/m/Y', strtotime($review->created_at)); ?></small>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require APPROOT . '/Views/templates/footer.php'; ?>
