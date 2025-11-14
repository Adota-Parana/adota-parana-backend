<?php

namespace App\Validators;

class ImageValidator
{
    private array $file;
    private array $errors = [];

    private bool $mustBeImage = false;
    private int $maxSize = 0; // bytes
    private array $allowedExtensions = [];
    private array $allowedMimes = [];

    public static function file(array $file): self
    {
        $instance = new self();
        $instance->file = $file;
        return $instance;
    }

    public function image(): self
    {
        $this->mustBeImage = true;
        return $this;
    }

    public function max(int $mb): self
    {
        $this->maxSize = $mb * 1024 * 1024;
        return $this;
    }

    public function mimes(array $exts): self
    {
        $this->allowedExtensions = array_map('strtolower', $exts);
        return $this;
    }

    public function validate(): array
    {
        $file = $this->file;

        // Se houver erro nativo no upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = "Erro ao enviar o arquivo.";
            return $this->errors;
        }

        // Extensão
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if (!empty($this->allowedExtensions) && !in_array($extension, $this->allowedExtensions)) {
            $this->errors[] = "Formato não permitido. Permitidos: " . implode(", ", $this->allowedExtensions);
        }

        // Tamanho
        if ($this->maxSize > 0 && $file['size'] > $this->maxSize) {
            $this->errors[] = "A imagem excede o tamanho máximo de " . ($this->maxSize / 1024 / 1024) . "MB";
        }

        // Verificação real da imagem
        if ($this->mustBeImage) {
            $info = @getimagesize($file['tmp_name']);
            if ($info === false) {
                $this->errors[] = "O arquivo não é uma imagem válida.";
                return $this->errors;
            }

            $mime = $info['mime'];
            $this->allowedMimes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mime, $this->allowedMimes)) {
                $this->errors[] = "Tipo MIME inválido para imagem.";
            }
        }

        return $this->errors;
    }
}
