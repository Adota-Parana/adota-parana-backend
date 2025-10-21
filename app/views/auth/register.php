<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
        <h3 class="text-center mb-4">Criar Conta</h3>

        <!-- Mensagem de sucesso geral -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <!-- Mensagem geral de erro -->
        <?php if (!empty($errors['general'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($errors['general']) ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nome Completo</label>
                <input 
                    type="text" 
                    class="form-control <?= !empty($errors['name']) ? 'is-invalid' : '' ?>" 
                    id="name" 
                    name="name" 
                    placeholder="Seu Nome Completo" 
                    value="<?= htmlspecialchars($user->name ?? '') ?>"
                    required
                >
                <?php if (!empty($errors['name'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['name']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input 
                    type="email" 
                    class="form-control <?= !empty($errors['email']) ? 'is-invalid' : '' ?>" 
                    id="email" 
                    name="email" 
                    placeholder="seuemail@email.com" 
                    value="<?= htmlspecialchars($user->email ?? '') ?>"
                    required
                >
                <?php if (!empty($errors['email'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['email']) ?>
                    </div>
                <?php endif; ?>
            </div>


            <div class="mb-3">
                <label for="phone" class="form-label">Phone</label>
                <input 
                    type="number" 
                    class="form-control <?= !empty($errors['phone']) ? 'is-invalid' : '' ?>" 
                    id="phone" 
                    name="phone" 
                    placeholder="(99)9999-9999" 
                    value="<?= htmlspecialchars($user->phone ?? '') ?>"
                    required
                >
                <?php if (!empty($errors['phone'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['phone']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input 
                    type="password" 
                    class="form-control <?= !empty($errors['password']) ? 'is-invalid' : '' ?>" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
                <?php if (!empty($errors['password'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['password']) ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirme a Senha</label>
                <input 
                    type="password" 
                    class="form-control <?= !empty($errors['password_confirmation']) ? 'is-invalid' : '' ?>" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="••••••••" 
                    required
                >
                <?php if (!empty($errors['password_confirmation'])): ?>
                    <div class="invalid-feedback">
                        <?= htmlspecialchars($errors['password_confirmation']) ?>
                    </div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary w-100">Criar Conta</button>

            <div class="text-center mt-3">
                <a href="/login">Já tem uma conta? Faça login.</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>
