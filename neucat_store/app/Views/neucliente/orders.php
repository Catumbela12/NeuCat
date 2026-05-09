<?php require APPROOT . '/Views/templates/header.php'; ?>

<main class="container" style="padding-top:40px; padding-bottom:80px; min-height:70vh;">
    <h1 class="section-title" style="margin-bottom:40px; text-align:left;">Meus <span class="text-gold">Pedidos</span></h1>

    <?php if(empty($data['orders'])): ?>
        <div style="background-color:var(--black-light); padding:40px; text-align:center; border-radius:4px; border:1px solid rgba(212,175,55,0.2);">
            <p style="color:var(--gray); font-size:18px; margin-bottom:20px;">Você ainda não fez nenhum pedido.</p>
            <a href="<?php echo URLROOT; ?>#products" class="btn btn-gold">Ver Produtos</a>
        </div>
    <?php else: ?>
        <div style="display:flex; flex-direction:column; gap:20px;">
            <?php foreach($data['orders'] as $order): ?>
                <div style="background-color:var(--black-light); border-radius:4px; border:1px solid rgba(212,175,55,0.2); overflow:hidden;">
                    <div style="background-color:rgba(212,175,55,0.1); padding:15px 20px; display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid rgba(212,175,55,0.2);">
                        <div>
                            <span style="color:var(--gold); font-weight:bold;">Pedido #<?php echo $order->id; ?></span>
                            <span style="color:var(--gray); margin-left:15px; font-size:14px;"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></span>
                        </div>
                        <div style="display:flex; align-items:center; gap:15px;">
                            <span style="font-size:18px; font-weight:bold;">R$ <?php echo number_format($order->total_price, 2, ',', '.'); ?></span>
                            <span style="padding:4px 10px; border-radius:15px; font-size:12px; font-weight:bold; 
                                <?php 
                                    if($order->status == 'completed') echo 'background-color:#4CAF50; color:white;';
                                    elseif($order->status == 'pending') echo 'background-color:#FFC107; color:black;';
                                    elseif($order->status == 'processing') echo 'background-color:#2196F3; color:white;';
                                    else echo 'background-color:#F44336; color:white;';
                                ?>">
                                <?php echo strtoupper($order->status); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div style="padding:20px;">
                        <h4 style="color:var(--gold); margin-bottom:15px;">Itens do Pedido:</h4>
                        <div style="display:flex; flex-direction:column; gap:15px;">
                            <?php foreach($order->items as $item): ?>
                                <div style="display:flex; align-items:center; gap:15px;">
                                    <img src="<?php echo URLROOT; ?>/img/<?php echo $item->image; ?>" alt="<?php echo htmlspecialchars($item->name); ?>" style="width:60px; height:60px; object-fit:cover; border-radius:4px; border:1px solid var(--gray);">
                                    <div style="flex:1;">
                                        <a href="<?php echo URLROOT; ?>/home/product/<?php echo $item->product_id; ?>" style="color:inherit; text-decoration:none; font-weight:bold;"><?php echo htmlspecialchars($item->name); ?></a>
                                        <p style="color:var(--gray); font-size:14px;">Quantidade: <?php echo $item->quantity; ?></p>
                                    </div>
                                    <div style="font-weight:bold;">
                                        R$ <?php echo number_format($item->price, 2, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<?php require APPROOT . '/Views/templates/footer.php'; ?>
