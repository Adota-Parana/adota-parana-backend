<?php require __DIR__ . '/../layouts/_header.php'; ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Adicionar Novo Pet</h3>
                </div>
                <div class="card-body">
                    <?php require __DIR__ . '/../layouts/_flash_message.php'; ?>

                    <form action="/pets/store" method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Nome do Pet</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Descrição</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Adicionar Pet</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/_footer.php'; ?>