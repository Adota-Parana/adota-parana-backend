<!-- app/views/user/profile/edit.php -->
<h1>Meu Perfil</h1>
<p>Atualize suas informações pessoais.</p>

<form action="/user/profile/update" method="POST">
    <div>
        <label for="name">Nome:</label><br>
        <input type="text" id="name" name="name" value="<?= htmlspecialchars($user->name) ?>" required>
    </div>
    <br>
    <div>
        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user->email) ?>" required>
    </div>
    <br>
    <div>
        <label for="phone">Telefone:</label><br>
        <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user->phone ?? '') ?>">
    </div>
    <hr>
    <br>
    <button type="submit">Salvar Alterações</button>
    <a href="/user/dashboard">Cancelar</a>
</form>
