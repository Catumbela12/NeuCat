<?php require APPROOT . '/Views/templates/admin_header.php'; ?>

<main class="container" style="padding-top:40px; padding-bottom:80px; min-height:70vh;">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:40px; flex-wrap:wrap; gap:16px;">
        <h1 class="section-title" style="margin-bottom:0; font-size:32px;">Painel de <span class="text-gold">Administração</span></h1>
        <div style="display:flex; gap:12px; align-items:center;">
            <span style="color:var(--gray); font-size:14px;">👤 <?php echo htmlspecialchars($_SESSION['admin_user'] ?? 'Admin'); ?></span>
            <a href="<?php echo URLROOT; ?>/auth/logout" class="btn"
               style="padding:8px 18px; font-size:13px; background:rgba(211,47,47,0.15); color:#ff6b6b; border:1px solid rgba(211,47,47,0.3); border-radius:4px;">Sair</a>
            <a href="<?php echo URLROOT; ?>/admin/categories" class="btn btn-outline-gold" style="padding:8px 18px;">Categorias</a>
            <a href="<?php echo URLROOT; ?>/admin/add" class="btn btn-gold">+ Adicionar Produto</a>
        </div>
    </div>

    <!-- PRODUTOS -->
    <h2 style="font-size:20px; margin-bottom:20px; color:var(--gold); letter-spacing:1px; text-transform:uppercase;">Produtos</h2>
    <div style="overflow-x:auto; margin-bottom:60px;">
        <table style="width:100%; border-collapse:collapse; text-align:left; background-color:var(--black-light); border-radius:4px; overflow:hidden;">
            <thead>
                <tr style="background-color:rgba(212,175,55,0.1); border-bottom:1px solid rgba(212,175,55,0.3);">
                    <th style="padding:15px; color:var(--gold);">Imagem</th>
                    <th style="padding:15px; color:var(--gold);">Nome</th>
                    <th style="padding:15px; color:var(--gold);">Categoria</th>
                    <th style="padding:15px; color:var(--gold);">Estoque</th>
                    <th style="padding:15px; color:var(--gold);">Preço</th>
                    <th style="padding:15px; color:var(--gold);">Promoção</th>
                    <th style="padding:15px; color:var(--gold);">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['products'] as $product): ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:15px;">
                            <img src="<?php echo URLROOT; ?>/img/<?php echo $product->image; ?>"
                                 alt="<?php echo $product->name; ?>"
                                 style="width:50px; height:50px; object-fit:cover; border-radius:4px;"
                                 onerror="this.src='https://via.placeholder.com/50x50/111/d4af37?text=N'">
                        </td>
                        <td style="padding:15px;"><?php echo $product->name; ?></td>
                        <td style="padding:15px;"><?php echo $product->category_name ?? '–'; ?></td>
                        <td style="padding:15px;"><?php echo $product->stock_quantity; ?></td>
                        <td style="padding:15px;">R$ <?php echo number_format($product->price, 2, ',', '.'); ?></td>
                        <td style="padding:15px;">
                            <?php if($product->promotional_price): ?>
                                <span style="color:#4CAF50;">R$ <?php echo number_format($product->promotional_price, 2, ',', '.'); ?></span>
                            <?php else: ?>
                                <span style="color:var(--gray);">–</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:15px; display:flex; gap:10px; align-items:center; height:80px;">
                            <a href="<?php echo URLROOT; ?>/admin/edit/<?php echo $product->id; ?>"
                               class="btn btn-outline-gold" style="padding:8px 16px; font-size:12px;">Editar</a>
                            <form action="<?php echo URLROOT; ?>/admin/delete/<?php echo $product->id; ?>"
                                  method="POST" style="margin:0;"
                                  onsubmit="return confirm('Tem certeza que deseja deletar este produto?');">
                                <button type="submit" class="btn"
                                        style="padding:8px 16px; font-size:12px; background-color:#d32f2f; color:white; border:none;">Deletar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($data['products'])): ?>
                    <tr><td colspan="7" style="padding:30px; text-align:center; color:var(--gray);">Nenhum produto encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PEDIDOS -->
    <h2 style="font-size:20px; margin-bottom:20px; color:var(--gold); letter-spacing:1px; text-transform:uppercase;">
        Pedidos <span style="font-size:14px; color:var(--gray); font-weight:400;">(<?php echo count($data['orders'] ?? []); ?>)</span>
    </h2>
    <div style="overflow-x:auto; margin-bottom:60px;">
        <table style="width:100%; border-collapse:collapse; text-align:left; background-color:var(--black-light); border-radius:4px; overflow:hidden;">
            <thead>
                <tr style="background-color:rgba(212,175,55,0.1); border-bottom:1px solid rgba(212,175,55,0.3);">
                    <th style="padding:15px; color:var(--gold);">ID</th>
                    <th style="padding:15px; color:var(--gold);">Cliente</th>
                    <th style="padding:15px; color:var(--gold);">Total</th>
                    <th style="padding:15px; color:var(--gold);">Status</th>
                    <th style="padding:15px; color:var(--gold);">Data</th>
                    <th style="padding:15px; color:var(--gold);">Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['orders'] as $order): ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:15px;">#<?php echo $order->id; ?></td>
                        <td style="padding:15px;"><?php echo htmlspecialchars($order->customer_name ?? $order->guest_email); ?></td>
                        <td style="padding:15px;">R$ <?php echo number_format($order->total_price, 2, ',', '.'); ?></td>
                        <td style="padding:15px;">
                            <span style="padding:4px 8px; border-radius:4px; font-size:12px; background:<?php echo $order->status == 'completed' ? '#4CAF50' : '#FF9800'; ?>; color:white;">
                                <?php echo ucfirst($order->status); ?>
                            </span>
                        </td>
                        <td style="padding:15px; color:var(--gray);"><?php echo date('d/m/Y H:i', strtotime($order->created_at)); ?></td>
                        <td style="padding:15px;">
                            <form action="<?php echo URLROOT; ?>/admin/updateOrderStatus/<?php echo $order->id; ?>" method="POST" style="display:flex; gap:8px;">
                                <select name="status" style="padding:4px; background:var(--black); color:white; border:1px solid var(--gold); border-radius:4px;">
                                    <option value="pending" <?php echo $order->status == 'pending' ? 'selected' : ''; ?>>Pendente</option>
                                    <option value="processing" <?php echo $order->status == 'processing' ? 'selected' : ''; ?>>Processando</option>
                                    <option value="completed" <?php echo $order->status == 'completed' ? 'selected' : ''; ?>>Concluído</option>
                                    <option value="cancelled" <?php echo $order->status == 'cancelled' ? 'selected' : ''; ?>>Cancelado</option>
                                </select>
                                <button type="submit" class="btn btn-gold" style="padding:4px 8px; font-size:12px;">Salvar</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($data['orders'])): ?>
                    <tr><td colspan="6" style="padding:30px; text-align:center; color:var(--gray);">Nenhum pedido encontrado.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- CLIENTES -->
    <h2 style="font-size:20px; margin-bottom:20px; color:var(--gold); letter-spacing:1px; text-transform:uppercase;">
        NeuCustomers <span style="font-size:14px; color:var(--gray); font-weight:400;">(<?php echo count($data['customers']); ?> cadastrados)</span>
    </h2>
    <div style="overflow-x:auto;">
        <table style="width:100%; border-collapse:collapse; text-align:left; background-color:var(--black-light); border-radius:4px; overflow:hidden;">
            <thead>
                <tr style="background-color:rgba(212,175,55,0.1); border-bottom:1px solid rgba(212,175,55,0.3);">
                    <th style="padding:15px; color:var(--gold);">#</th>
                    <th style="padding:15px; color:var(--gold);">Nome</th>
                    <th style="padding:15px; color:var(--gold);">E-mail</th>
                    <th style="padding:15px; color:var(--gold);">Telefone</th>
                    <th style="padding:15px; color:var(--gold);">Nascimento</th>
                    <th style="padding:15px; color:var(--gold);">Sexo</th>
                    <th style="padding:15px; color:var(--gold);">Cadastrado em</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($data['customers'] as $c): ?>
                    <tr style="border-bottom:1px solid rgba(255,255,255,0.05);">
                        <td style="padding:15px; color:var(--gray);"><?php echo $c->id; ?></td>
                        <td style="padding:15px;"><?php echo htmlspecialchars($c->name); ?></td>
                        <td style="padding:15px; color:var(--gray);"><?php echo htmlspecialchars($c->email ?: '–'); ?></td>
                        <td style="padding:15px; color:var(--gray);"><?php echo htmlspecialchars($c->phone ?: '–'); ?></td>
                        <td style="padding:15px; color:var(--gray);"><?php echo $c->birth_date ? date('d/m/Y', strtotime($c->birth_date)) : '–'; ?></td>
                        <td style="padding:15px; color:var(--gray);">
                            <?php $g = ['M'=>'Masculino','F'=>'Feminino','O'=>'Outro']; echo $g[$c->gender] ?? '–'; ?>
                        </td>
                        <td style="padding:15px; color:var(--gray);"><?php echo $c->created_at ? date('d/m/Y H:i', strtotime($c->created_at)) : '–'; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if(empty($data['customers'])): ?>
                    <tr><td colspan="7" style="padding:30px; text-align:center; color:var(--gray);">Nenhum cliente cadastrado ainda.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</main>

<?php require APPROOT . '/Views/templates/admin_footer.php'; ?>
