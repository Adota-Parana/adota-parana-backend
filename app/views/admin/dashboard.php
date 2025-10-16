<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Painel Administrativo</h1>

    <?php require __DIR__ . '/../layouts/_flash_message.php'; ?>

    <p>Bem-vindo ao painel de administração, <?= htmlspecialchars($currentUser->name) ?>!</p>
    
    <div class="list-group">
        <a href="/admin/users" class="list-group-item list-group-item-action">
            Gerenciar Usuários
        </a>
        <a href="/feed" class="list-group-item list-group-item-action">
            Ver Feed de Pets
        </a>
    </div>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>