<?php require APPROOT . '/Views/templates/header.php'; ?>

<main class="container" style="padding-top:40px; padding-bottom:80px; min-height:70vh;">
    <h1 class="section-title" style="margin-bottom:40px; text-align:left;">Meus <span class="text-gold">Favoritos</span></h1>

    <?php if(empty($data['favorites'])): ?>
        <div style="background-color:var(--black-light); padding:40px; text-align:center; border-radius:4px; border:1px solid rgba(212,175,55,0.2);">
            <p style="color:var(--gray); font-size:18px; margin-bottom:20px;">Você ainda não tem nenhum produto favorito.</p>
            <a href="<?php echo URLROOT; ?>#products" class="btn btn-gold">Explorar Produtos</a>
        </div>
    <?php else: ?>
        <div class="product-grid">
            <?php foreach($data['favorites'] as $product): ?>
                <?php
                    $hasPromo     = !empty($product->promotional_price);
                    $displayPrice = $hasPromo ? $product->promotional_price : $product->price;
                ?>
                <div class="product-card" style="position:relative;">
                    <a href="<?php echo URLROOT; ?>/home/toggleFavorite/<?php echo $product->id; ?>" style="position:absolute; top:10px; right:10px; background:rgba(0,0,0,0.5); border-radius:50%; width:35px; height:35px; display:flex; align-items:center; justify-content:center; text-decoration:none; z-index:10; font-size:20px;">
                        ❤️
                    </a>
                    <a href="<?php echo URLROOT; ?>/home/product/<?php echo $product->id; ?>" style="text-decoration:none; color:inherit; display:block;">
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>" alt="<?php echo htmlspecialchars($product->name); ?>">
                            <?php if ($hasPromo): ?>
                                <div class="product-badge-promo">OFERTA</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo htmlspecialchars($product->name); ?></h3>
                            <?php if ($hasPromo): ?>
                                <p class="price" style="display:flex; flex-direction:column; align-items:center; gap:5px; margin-bottom:15px;">
                                    <span style="text-decoration:line-through; color:var(--gray); font-size:16px; font-weight:normal;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></span>
                                    <span>R$ <?php echo number_format($product->promotional_price, 2, ',', '.'); ?></span>
                                </p>
                            <?php else: ?>
                                <p class="price" style="margin-bottom:15px;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div style="padding: 0 20px 20px 20px;">
                        <button class="btn btn-outline-gold w-100 add-to-cart-btn" data-id="<?php echo $product->id; ?>" data-name="<?php echo htmlspecialchars($product->name); ?>" data-price="<?php echo $displayPrice; ?>" data-image="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>">Adicionar ao Carrinho</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require APPROOT . '/Views/templates/footer.php'; ?>
