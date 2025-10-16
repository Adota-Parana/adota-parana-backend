<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Feed de Pets</h1>
    </div>

    <?php require __DIR__ . '/../layouts/_flash_message.php'; ?>

    <div class="row">
        <?php foreach ($pets as $pet): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="https://via.placeholder.com/300x200" class="card-img-top" alt="Foto do pet">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($pet->name) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($pet->description) ?></p>
                        <a href="#" class="btn btn-primary">Ver detalhes</a>

                        <?php if ($currentUser && ($currentUser->id === $pet->user_id || $currentUser->isAdmin())):
                         ?>
                            <a href="/pets/<?= $pet->id ?>/edit" class="btn btn-secondary">Editar</a>
                            <form action="/pets/<?= $pet->id ?>/delete" method="POST" style="display:inline;">
                                <button type="submit" class="btn btn-danger">Excluir</button>
                            </form>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>