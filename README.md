# Recuperar-Senha
Recuperar senha por token enviado em email.

#Requisitos:
Será necessário instalar o PHPMailer no projeto. Siga o passo a passo:
* 1 - Instale o composer em getcomposer.org
* 2 - Crie um arquivo composer.json no diretório do seu projeto:
```
{
    "require": {
        "phpmailer/phpmailer": "^6.5"
    }
}
```
* 3 - Execute o comando composer install no terminal:
```
composer install
```

Após isso será necessário colocar seu email e a senha de app:
É necessário acessar https://myaccount.google.com/ e pesquisar por "Senhas de App", criar um aplicativo com um nome qualquer e será fornecida uma senha. Essa senha dará acesso ao seu email sem ser afetada pelas seguranças, por isso tenha cuidado!

Em seguida substitua o que for necessário em /assets/actions/password_reset.php
