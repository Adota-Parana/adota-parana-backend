<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container mt-5">
    <h1 class="mb-4">Gerenciar Usuários</h1>

    <?php require __DIR__ . '/../layouts/_flash_message.phtml'; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>E-mail</th>
                <th>Função</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= htmlspecialchars($user->name) ?></td>
                    <td><?= htmlspecialchars($user->email) ?></td>
                    <td><?= $user->role ?></td>
                    <td>
                        <form action="/admin/users/<?= $user->id ?>/delete" method="POST" style="display:inline;">
                            <button type="submit" class="btn btn-sm btn-danger">Excluir</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>