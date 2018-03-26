#e.Rede PHP SDK

Esta é uma implementação framework agnostic em PHP dos serviços RESTful do e.Rede da [UseRede](https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#documentacao).

UseRede (a.k.a Redecard) é uma das adquirentes de cartão de crédito líderes no Brasil.

Antes de utilizar, tenha certeza de que o produto foi contratado para seu ponto de venda.

## Principais recursos

* [x] Pagamentos por cartão de crédito.
* [x] Pagamentos por cartão de débito / autenticação.
* [x] Cancelamento de autorização / captura.
* [x] Consulta de transações.

## Limitações

Por envolver a interface de usuário da aplicação, o SDK funciona apenas como um framework para criação das transações. 
Nos casos onde a autorização é direta (sem uso de 3DS), não há limitação; mas nos casos onde é necessário a autenticação,
o desenvolvedor deverá utilizar o SDK para gerar a transação e, com o link retornado pela Rede, providenciar o 
redirecionamento do usuário em sua aplicação.

## Instalando o e.Rede

A melhor forma de instalar este pacote é via [Composer](http://getcomposer.org).

Se já possui um arquivo `composer.json`, basta adicionar a seguinte dependência ao seu projeto:

```json
"require": {
    "filipegar/erede": "^1.0"
}
```

Com a dependência adicionada ao `composer.json`, basta executar:

```
composer install
```

Alternativamente, você pode executar diretamente em seu terminal:

```
composer require "filipegar/erede"
```

## Utilizando o SDK

Para criar um pagamento simples com cartão de crédito com o SDK, basta fazer:

### Criando um pagamento com cartão de crédito

```php
<?php
require 'vendor/autoload.php';

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Acquirer\ERedeClient;
use Filipegar\eRede\Acquirer\Transaction;
use Filipegar\eRede\Acquirer\Refund;
use Filipegar\eRede\Acquirer\Url;
use Filipegar\eRede\Merchant;
use Filipegar\eRede\Acquirer\Requests\ERedeErrorException;

//Configure suas credenciais - PV e token
$merchant = new Merchant("PVestabelecimento", "TOKEN");
//Crie seu cliente da SDK
$eRede = new ERedeClient($merchant, Environment::sandbox());
// Crie uma instância de Transaction informando o ID do pedido na loja
$transacao = (new Transaction("IDpedido"))->setCapture(false)->setSoftDescriptor('PEDIDO 12345');
// Crie uma instância de Payment informando o valor do pagamento
$payment = $transacao->payment(123456);
// Crie uma instância de Credit Card utilizando os dados de teste
// disponíveis no manual de integração
$card = $transacao->creditCard('CVV/CVV2')
        ->setCardHolderName('Fulano da Silva')
        ->setCardNumber('0000000000000001')
        ->setExpirationMonth('01')->setExpirationYear('2019');

// Envie o pagamento para a Rede
try {
    // Utilizando o cliente SDK criado com Merchant + Environment
    $transacao = $eRede->authorize($transacao);

    // Com a venda criada na Cielo, já temos o ID do pagamento, TID e demais
    // dados retornados pela Cielo
    $transactionTid = $transacao->getTid();

    // Com o ID do pagamento, podemos fazer sua captura, se ela não tiver sido capturada ainda
    $captura = $eRede->captureTransaction($transactionTid, 123456);

    // E também podemos fazer seu cancelamento, se for o caso
    $refund = (new Refund())->setTid($transactionTid)->setAmount(123456)->url('CALLBACKurl', URL::URL_CALLBACK);
    $refund = $eRede->refundTransaction($refund);
} catch (ERedeErrorException $e) {
    // Em caso de erros de integração, podemos tratar o erro aqui.
    // os códigos de erro estão todos disponíveis no manual de integração.
    $error = $e->getMessage();
    //os erros de transferência do Guzzle estão em getPrevious().
    $erroERede = (string) $e->getPrevious()->getRequest()->getBody();
}
// ...
```

### Criando um pagamento com cartão de débito / autenticada

```php
<?php
require 'vendor/autoload.php';

use Filipegar\eRede\Acquirer\Environment;
use Filipegar\eRede\Acquirer\ERedeClient;
use Filipegar\eRede\Acquirer\Transaction;
use Filipegar\eRede\Acquirer\Url;
use Filipegar\eRede\Acquirer\ThreeDSecure;
use Filipegar\eRede\Merchant;
use Filipegar\eRede\Acquirer\Requests\ERedeErrorException;

//Configure suas credenciais - PV e token
$merchant = new Merchant("PVestabelecimento", "TOKEN");
//Crie seu cliente da SDK
$eRede = new ERedeClient($merchant, Environment::sandbox());
// Crie uma instância de Transaction informando o ID do pedido na loja
$transacao = (new Transaction("IDpedido"))->setCapture(false)->setSoftDescriptor('PEDIDO 12345');
// Crie uma instância de Payment informando o valor do pagamento
$payment = $transacao->payment(123456);
// Crie uma instância de Credit Card utilizando os dados de teste
// disponíveis no manual de integração
$card = $transacao->debitCard('CVV/CVV2')
        ->setCardHolderName('Fulano da Silva')
        ->setCardNumber('0000000000000001')
        ->setExpirationMonth('01')->setExpirationYear('2019');

// Obrigatório utilizar 3DS - 3D Secure - transação autenticada
$threeD = $transacao->threeDSecure(ThreeDSecure::MPI_EREDE, ThreeDSecure::FAILURE_DECLINE)->setUserAgent('USER_AGENT_string');
// Defina as URLs para redirecionar seu cliente de volta para seu site.
$transacao->url('https://teste.com.br/erede/sucesso', Url::URL_SUCCESS)->url('https://teste.com.br/erede/falha', Url::URL_FAILURE);

// Envie o pagamento para a Rede
try {
    // Utilizando o cliente SDK criado com Merchant + Environment
    $transacao = $eRede->authorize($transacao);

    // Redirecione o usuário de sua aplicação para a URL de autenticação.
    $transacao->getThreeDSecure()->getRedirectUrl();
    
} catch (ERedeErrorException $e) {
    // Em caso de erros de integração, podemos tratar o erro aqui.
    // os códigos de erro estão todos disponíveis no manual de integração.
    $error = $e->getMessage();
    //os erros de transferência do Guzzle estão em getPrevious().
    $erroERede = (string) $e->getPrevious()->getRequest()->getBody();
}
// ...
```
*Note que o e.Rede não gera um TID, NSU ou Authorization Code para as transações autenticadas que não foram concluídas.
No Sandbox não foi possível consultar estas transações nem mesmo com o referenceId da loja. Por isto, o objeto Transaction
devolvido na resposta do método authorize conterá apenas o link para redirecionamento, status da solicitação (220) e a data/hora da solicitação.*

### Consultando uma transação
 
 ```php
 <?php
 require 'vendor/autoload.php';
 
 use Filipegar\eRede\Acquirer\Environment;
 use Filipegar\eRede\Acquirer\ERedeClient;
 use Filipegar\eRede\Merchant;
 use Filipegar\eRede\Acquirer\Requests\ERedeErrorException;
 
 //Configure suas credenciais - PV e token
 $merchant = new Merchant("PVestabelecimento", "TOKEN");
 //Crie seu cliente da SDK
 $eRede = new ERedeClient($merchant, Environment::sandbox());

 // Consulta de status da transação
 try {
     $query = $eRede->queryTransaction('TIDcom20digitos');
     // Ou ainda via Referencia da loja
     $query = $eRede->queryTransactionReference('Reference');
     
     // Consulte os dados da transação via getTransaction()
     $nsu = $query->getTransaction()->getNsu();
     $cardBin = $query->getTransaction()->getCard()->getCardBin();
     $amount = $query->getTransaction()->getPayment()->getAmount();
     
     // Consulte os dados da captura
     $nsuCaptura = $query->getCapture()->nsu;
     
     // Ou consulte os dados de qualquer cancelamento
     $refundId = $query->getRefunds()[0]->getRefundId();
 } catch (ERedeErrorException $e) {
     // Em caso de erros de integração, podemos tratar o erro aqui.
     // os códigos de erro estão todos disponíveis no manual de integração.
     $error = $e->getMessage();
     //os erros de transferência do Guzzle estão em getPrevious().
     $erroERede = (string) $e->getPrevious()->getRequest()->getBody();
 }
 // ...
 ```
 
 ## Cartões para testes
 
 Os cartões de teste estão disponíveis da documentação do e.Rede [neste link](https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#tutorial-cartao).
 
 Clique em Cadastre-se para obter seu token e número de PV para testar este SDK.
 
 ## Documentação do e.Rede
 
 A documentação do e.Rede está disponível no site da Rede [neste link](https://www.userede.com.br/desenvolvedores/pt/produto/e-Rede#documentacao).
 
 Se você encontrar um comportamento diferente do documentado, por favor, reporte um issue para verificação.
 
 ## Licença
 
 Este pacote de código aberto segue os termos do [MIT license](https://opensource.org/licenses/MIT).