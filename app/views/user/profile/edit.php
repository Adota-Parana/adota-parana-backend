<h1>Meu Perfil</h1>
<p>Atualize suas informações pessoais.</p>

<form action="/user/profile/update" method="POST" novalidate>
    <div>
        <label for="name">Nome:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
        <?php if (!empty($errors['name'])): ?>
            <div style="color:red; font-size:0.9em;">
                <?= htmlspecialchars($errors['name']) ?>
            </div>
        <?php endif; ?>
    </div>
    <br>

    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
        <?php if (!empty($errors['email'])): ?>
            <div style="color:red; font-size:0.9em;">
                <?= htmlspecialchars($errors['email']) ?>
            </div>
        <?php endif; ?>
    </div>
    <br>

    <div>
        <label for="phone">Telefone:</label><br>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>">
        <?php if (!empty($errors['phone'])): ?>
            <div style="color:red; font-size:0.9em;">
                <?= htmlspecialchars($errors['phone']) ?>
            </div>
        <?php endif; ?>
    </div>
    <hr>
    <br>

    <button type="submit">Salvar Alterações</button>
    <a href="/user/dashboard">Cancelar</a>
</form>
