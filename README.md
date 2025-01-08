BotConversa
Biblioteca PHP para integrar com o BotConversa API.

Essa biblioteca facilita a comunicação com o BotConversa, fornecendo métodos prontos para gerenciar assinantes, enviar mensagens e mais.

# Instalação
composer require jhenrique/bot-conversa

---------------------------------------------
# Configuração
Adicione um arquivo .env
Na raiz do seu projeto, crie um arquivo .env com as seguintes variáveis:

BASE_URL=https://api.botconversa.com
API_KEY=seu_api_key_aqui
API_TIMEOUT=30

Crie uma pasta com nome "logs" na raiz do projeto

-----------------------------------------------
# Carregue as variáveis de ambiente
Antes de usar a biblioteca, certifique-se de que as variáveis foram carregadas. Por exemplo:

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

-----------------------------------------------
# Uso
Exemplo de como usar a biblioteca:

use BotConversa\ApiClient;

$botconversa = new ApiClient();

// Obter informações de um contato por telefone
$subscriber = $botconversa->getSubscriberByPhone('5511999999999');
print_r($subscriber);

// Criar um novo contato
$newSubscriber = $botconversa->createSubscriber('5511988887777', 'João', 'Silva');
print_r($newSubscriber);

// Enviar uma mensagem para um contato
$response = $botconversa->sendMessage('contato_id', 'text', 'Olá, tudo bem?');
print_r($response);

-----------------------------------------------
# Funções
- Subscribers
    getSubscriberByPhone(string $phone)
    getSubscribers(int $page)
    createSubscriber(string $phone, string $first_name, string $last_name)
    deleteSubscriber(string $id)
    addTagSubscriber(string $id, string $id_tag) (*NOVO*)
    deleteTagSubscriber(string $id, string $id_tag) (*NOVO*)
    addSequencesSubscriber(string $id, string $id_sequence) (*NOVO*)
    deleteSequencesSubscriber(string $id, string $id_sequence) (*NOVO*)
    addCampaignsSubscriber(string $id, string $id_campaign) (*NOVO*)
    deleteCampaignsSubscriber(string $id, string $id_campaign) (*NOVO*)

- Message
    sendMessage(string $id, string $type, string $message)
    sendMessageFlow(string $id, string $flow) (*NOVO*)

- Tags
    getTags() (*NOVO*)

- Flows
    getFlows() (*NOVO*)

-----------------------------------------------
# Licença
Este projeto está licenciado sob a licença MIT.