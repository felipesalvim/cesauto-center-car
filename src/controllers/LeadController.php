<?php

declare(strict_types=1);

final class LeadController
{
    private const ENDPOINT = 'lead_form';

    public function __construct(private array $config)
    {
    }

    public function handlePost(): void
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            $this->redirectWithFlash('error', 'Método inválido.');
            return;
        }

        if (!Csrf::validate($_POST['csrf_token'] ?? null)) {
            $this->redirectWithFlash('error', 'Sessão expirada. Atualize a página e tente novamente.');
            return;
        }

        $ip = $this->clientIp();

        try {
            $pdo = Database::connection($this->config['db']);
            $rate = new RateLimit($pdo);

            $allowed = $rate->hit(
                $ip,
                self::ENDPOINT,
                (int) $this->config['rate_limit']['max'],
                (int) $this->config['rate_limit']['window']
            );

            if (!$allowed) {
                $this->redirectWithFlash('error', 'Muitas tentativas. Aguarde um minuto e tente de novo.');
                return;
            }

            $validated = $this->validate($_POST);
            if ($validated['errors'] !== []) {
                $_SESSION['form_old'] = [
                    'nome' => (string) ($validated['data']['nome'] ?? ''),
                    'email' => (string) ($validated['data']['email'] ?? ''),
                    'telefone' => (string) ($validated['data']['telefone'] ?? ''),
                    'modelo_carro' => (string) ($validated['data']['modelo_carro'] ?? ''),
                    'servico_interesse' => (string) ($validated['data']['servico_interesse'] ?? ''),
                ];
                $this->redirectWithFlash('error', implode(' ', $validated['errors']));
                return;
            }

            $lead = new Lead($pdo);
            $lead->create([
                'nome' => $validated['data']['nome'],
                'email' => $validated['data']['email'],
                'telefone' => $validated['data']['telefone'],
                'modelo_carro' => $validated['data']['modelo_carro'],
                'servico_interesse' => $validated['data']['servico_interesse'],
                'mensagem' => $validated['data']['mensagem'],
                'lgpd_consent' => 1,
                'ip' => $ip,
            ]);

            Csrf::rotate();
            unset($_SESSION['form_old']);
            $this->redirectWithFlash('success', 'Recebemos seu pedido. Em breve entraremos em contato.');
        } catch (Throwable $e) {
            error_log('LeadController: ' . $e->getMessage());
            $this->redirectWithFlash('error', 'Não foi possível enviar agora. Tente pelo WhatsApp ou mais tarde.');
        }
    }

    /**
     * @param array<string, mixed> $input
     * @return array{data: array<string, mixed>, errors: list<string>}
     */
    private function validate(array $input): array
    {
        $errors = [];

        $nome = trim((string) ($input['nome'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $telefone = preg_replace('/\D+/', '', (string) ($input['telefone'] ?? '')) ?? '';
        $modelo = trim((string) ($input['modelo_carro'] ?? ''));
        $servico = trim((string) ($input['servico_interesse'] ?? ''));
        $lgpd = isset($input['lgpd_consent']) && (string) $input['lgpd_consent'] === '1';

        if ($nome === '' || mb_strlen($nome) > 120) {
            $errors[] = 'Informe um nome válido.';
        }

        if ($email !== '' && (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 180)) {
            $errors[] = 'Informe um e-mail válido ou deixe em branco.';
        }

        if ($telefone === '' || strlen($telefone) < 10 || strlen($telefone) > 13) {
            $errors[] = 'Informe um telefone/WhatsApp válido com DDD.';
        }

        if (mb_strlen($modelo) > 80) {
            $errors[] = 'Modelo do carro muito longo.';
        }

        if ($servico === '' || !array_key_exists($servico, Lead::SERVICOS)) {
            $errors[] = 'Selecione um serviço de interesse.';
        }

        if (!$lgpd) {
            $errors[] = 'É necessário aceitar a política de privacidade (LGPD).';
        }

        return [
            'data' => [
                'nome' => $nome,
                'email' => $email === '' ? null : $email,
                'telefone' => $telefone,
                'modelo_carro' => $modelo === '' ? null : $modelo,
                'servico_interesse' => $servico,
                'mensagem' => null,
            ],
            'errors' => $errors,
        ];
    }

    private function clientIp(): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        return is_string($ip) ? substr($ip, 0, 45) : '0.0.0.0';
    }

    private function redirectWithFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
        ];

        $base = $this->config['app_url'] !== ''
            ? $this->config['app_url'] . '/index.php'
            : 'index.php';

        header('Location: ' . $base . '#contato', true, 303);
        exit;
    }
}
