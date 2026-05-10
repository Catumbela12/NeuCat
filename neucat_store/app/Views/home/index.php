<?php require APPROOT . '/Views/templates/header.php'; ?>

<main>
    <section class="hero">
        <div class="hero-content">
            <h1>Descubra a<br><span class="text-gold">Sua Essência</span></h1>
            <p>A nova coleção Neucat combina a profundidade do preto com o luxo e a luz do dourado. Exclusividade e sofisticação.</p>
            <a href="#products" class="btn btn-gold">Ver Coleção</a>
        </div>
    </section>

    <?php if (empty($_SESSION['customer_logged_in'])): ?>
    <section class="promo-banner">
        <div class="container">
            <div class="promo-banner-inner">
                <div class="promo-banner-icon">⭐</div>
                <div class="promo-banner-text">
                    <strong>Preços Exclusivos para NeuCustomers</strong>
                    <span>Cadastre-se gratuitamente e acesse descontos especiais em produtos selecionados</span>
                </div>
                <div class="promo-banner-actions">
                    <a href="<?php echo URLROOT; ?>/neucliente/register" class="btn btn-gold" style="padding:10px 24px; font-size:13px;">Cadastrar grátis</a>
                    <a href="<?php echo URLROOT; ?>/neucliente/login" class="btn btn-outline-gold" style="padding:10px 24px; font-size:13px;">Já tenho conta</a>
                </div>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="promo-banner promo-banner-active">
        <div class="container">
            <div class="promo-banner-inner">
                <div class="promo-banner-icon">💎</div>
                <div class="promo-banner-text">
                    <strong>Olá, <?php echo htmlspecialchars($_SESSION['customer_name']); ?>! Você tem acesso aos preços exclusivos NeuCustomer</strong>
                    <span>Os preços promocionais já estão aplicados abaixo</span>
                </div>
                <a href="<?php echo URLROOT; ?>/neucliente/logout" class="btn btn-outline-gold" style="padding:10px 24px; font-size:13px; white-space:nowrap;">Sair da conta</a>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Categorias -->
    <section class="container" style="padding-top: 40px; text-align: center;">
        <div style="display:flex; justify-content:center; gap:10px; flex-wrap:wrap;">
            <a href="<?php echo URLROOT; ?>#products" class="btn <?php echo empty($data['categoryId']) ? 'btn-gold' : 'btn-outline-gold'; ?>" style="border-radius:20px; padding:8px 20px;">Todas</a>
            <?php foreach($data['categories'] as $cat): ?>
                <a href="<?php echo URLROOT; ?>?category=<?php echo $cat->id; ?>#products" class="btn <?php echo ($data['categoryId'] == $cat->id) ? 'btn-gold' : 'btn-outline-gold'; ?>" style="border-radius:20px; padding:8px 20px;"><?php echo htmlspecialchars($cat->name); ?></a>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Mais Vendidos -->
    <?php if(empty($data['search']) && empty($data['categoryId']) && !empty($data['bestsellers'])): ?>
    <section class="products-section container" style="padding-top: 40px;">
        <h2 class="section-title">Mais <span class="text-gold">Vendidos</span></h2>
        <div class="product-grid">
            <?php foreach($data['bestsellers'] as $product): ?>
                <?php
                    $isCustomer   = !empty($_SESSION['customer_logged_in']);
                    $hasPromo     = !empty($product->promotional_price);
                    $displayPrice = ($isCustomer && $hasPromo) ? $product->promotional_price : $product->price;
                ?>
                <div class="product-card">
                    <a href="<?php echo URLROOT; ?>/home/product/<?php echo $product->id; ?>" style="text-decoration:none; color:inherit; display:block;">
                        <div class="product-image">
                            <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>"
                                 alt="<?php echo $product->name; ?>"
                                 onerror="this.src='https://via.placeholder.com/300x300/111/d4af37?text=NEUCAT'">
                            <?php if ($hasPromo && $isCustomer): ?>
                                <div class="product-badge-promo">OFERTA</div>
                            <?php elseif ($hasPromo && !$isCustomer): ?>
                                <div class="product-badge-locked">🔒 Preço exclusivo</div>
                            <?php endif; ?>
                        </div>
                        <div class="product-info">
                            <h3><?php echo $product->name; ?></h3>
                            <?php if ($isCustomer && $hasPromo): ?>
                                <p class="price" style="display:flex; flex-direction:column; align-items:center; gap:5px; margin-bottom:15px;">
                                    <span style="text-decoration:line-through; color:var(--gray); font-size:16px; font-weight:normal;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></span>
                                    <span>R$ <?php echo number_format($product->promotional_price, 2, ',', '.'); ?></span>
                                </p>
                            <?php elseif (!$isCustomer && $hasPromo): ?>
                                <p class="price" style="margin-bottom:10px;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></p>
                            <?php else: ?>
                                <p class="price" style="margin-bottom:15px;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></p>
                            <?php endif; ?>
                        </div>
                    </a>
                    <div style="padding: 0 20px 20px 20px;">
                        <button class="btn btn-outline-gold w-100 add-to-cart-btn" data-id="<?php echo $product->id; ?>" data-name="<?php echo htmlspecialchars($product->name); ?>" data-price="<?php echo $displayPrice; ?>" data-image="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>">Adicionar</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <section id="products" class="products-section container">
        <h2 class="section-title">
            <?php if(!empty($data['search'])) echo 'Resultados para "<span class="text-gold">'.htmlspecialchars($data['search']).'</span>"';
                  elseif(!empty($data['categoryId'])) echo 'Produtos na <span class="text-gold">Categoria</span>';
                  else echo 'Todos os <span class="text-gold">Produtos</span>'; ?>
        </h2>
        
        <?php if(empty($data['products'])): ?>
            <p style="text-align:center; color:var(--gray); margin-top:40px;">Nenhum produto encontrado.</p>
        <?php else: ?>
            <div class="product-grid">
                <?php foreach($data['products'] as $product): ?>
                    <?php
                        $isCustomer   = !empty($_SESSION['customer_logged_in']);
                        $hasPromo     = !empty($product->promotional_price);
                        $displayPrice = ($isCustomer && $hasPromo) ? $product->promotional_price : $product->price;
                    ?>
                    <div class="product-card">
                        <a href="<?php echo URLROOT; ?>/home/product/<?php echo $product->id; ?>" style="text-decoration:none; color:inherit; display:block;">
                            <div class="product-image">
                                <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>"
                                     alt="<?php echo $product->name; ?>"
                                     onerror="this.src='https://via.placeholder.com/300x300/111/d4af37?text=NEUCAT'">
                                <?php if ($hasPromo && $isCustomer): ?>
                                    <div class="product-badge-promo">OFERTA</div>
                                <?php elseif ($hasPromo && !$isCustomer): ?>
                                    <div class="product-badge-locked">🔒 Preço exclusivo</div>
                                <?php endif; ?>
                            </div>
                            <div class="product-info">
                                <h3><?php echo $product->name; ?></h3>
                                
                                <?php if ($isCustomer && $hasPromo): ?>
                                    <p class="price" style="display:flex; flex-direction:column; align-items:center; gap:5px; margin-bottom:15px;">
                                        <span style="text-decoration:line-through; color:var(--gray); font-size:16px; font-weight:normal;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></span>
                                        <span>R$ <?php echo number_format($product->promotional_price, 2, ',', '.'); ?></span>
                                    </p>
                                <?php elseif (!$isCustomer && $hasPromo): ?>
                                    <p class="price" style="margin-bottom:10px;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></p>
                                    <p class="price-locked" style="margin-bottom:15px;"><span style="color:var(--gold); font-size:12px;">🔒 Ver preço de cliente</span></p>
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
    </section>

</main>

<?php require APPROOT . '/Views/templates/footer.php'; ?>
