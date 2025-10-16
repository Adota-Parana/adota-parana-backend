<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
        <h3 class="text-center mb-4">Criar Conta</h3>

        <?php require __DIR__ . '/../layouts/_flash_message.php'; ?>

        <form action="/register" method="POST" novalidate>
            <div class="mb-3">
                <label for="name" class="form-label">Nome Completo</label>
                <input 
                    type="text" 
                    class="form-control" 
                    id="name" 
                    name="name" 
                    placeholder="Seu Nome Completo" 
                    required
                >
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input 
                    type="email" 
                    class="form-control" 
                    id="email" 
                    name="email" 
                    placeholder="seuemail@email.com" 
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Senha</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    placeholder="••••••••" 
                    required
                >
            </div>
            
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirme a Senha</label>
                <input 
                    type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    placeholder="••••••••" 
                    required
                >
            </div>

            <button type="submit" class="btn btn-primary w-100">Criar Conta</button>

            <div class="text-center mt-3">
                <a href="/login">Já tem uma conta? Faça login.</a>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>