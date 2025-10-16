<h1>Gerenciamento de Usuários</h1>
<p>Aqui você pode ver e deletar usuários do sistema.</p>

<table border="1" style="width:100%; border-collapse: collapse;">
    <thead>
        <tr>
            <th style="padding: 8px;">ID</th>
            <th style="padding: 8px;">Nome</th>
            <th style="padding: 8px;">Email</th>
            <th style="padding: 8px;">Perfil</th>
            <th style="padding: 8px;">Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user): ?>
            <tr>
                <td style="padding: 8px;"><?= $user->id ?></td>
                <td style="padding: 8px;"><?= htmlspecialchars($user->name) ?></td>
                <td style="padding: 8px;"><?= htmlspecialchars($user->email) ?></td>
                <td style="padding: 8px;"><?= htmlspecialchars($user->role) ?></td>
                <td style="padding: 8px; text-align: center;">
                    <form action="/admin/users/delete/<?= $user->id ?>" method="POST" style="display:inline;">
                        <button type="submit" onclick="return confirm('Tem certeza que deseja deletar este usuário? Esta ação não pode ser desfeita.');">
                            Deletar
                        </button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<br>
<a href="/admin/dashboard">Voltar ao Dashboard</a>
