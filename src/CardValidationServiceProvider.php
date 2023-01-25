<?php
namespace Codificar\CardValidation;
use Illuminate\Support\ServiceProvider;

class CardValidationServiceProvider extends ServiceProvider {

    public function boot()
    {
        // Load routes (carrega as rotas)
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        // Load trans files (Carrega tos arquivos de traducao) 
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'cardValidationTrans');
    }

    public function register()
    {

    }
}
?>