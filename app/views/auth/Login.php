s <div class="container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg p-4" style="max-width: 400px; width: 100%; border-radius: 1rem;">
            <h3 class="text-center mb-4">Entrar</h3>

            <?php require __DIR__ . '/../../views/layouts/_flash_message.phtml'; ?>

            <form action="/login" method="POST" novalidate>
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

                <button type="submit" class="btn btn-primary w-100">Entrar</button>

                <div class="text-center mt-3">
                    <a href="/register">Não tem uma conta? Crie uma aqui.</a>
                </div>
            </form>
        </div>
    </div>