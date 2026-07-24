<?php

declare(strict_types=1);

final class RateLimit
{
    public function __construct(private PDO $pdo)
    {
    }

    /**
     * Registra a tentativa e retorna true se estiver dentro do limite.
     * false = limite excedido (não grava o hit além do necessário para a contagem atual).
     */
    public function hit(string $ip, string $endpoint, int $max, int $windowSeconds): bool
    {
        $window = max(1, $windowSeconds);

        $this->pdo->beginTransaction();

        try {
            // INTERVAL com inteiro sanitizado (não vem de input do usuário).
            $stmt = $this->pdo->prepare(
                'SELECT COUNT(*) AS total
                 FROM rate_limits
                 WHERE ip = :ip
                   AND endpoint = :endpoint
                   AND created_at >= (NOW() - INTERVAL ' . $window . ' SECOND)'
            );
            $stmt->execute([
                ':ip' => $ip,
                ':endpoint' => $endpoint,
            ]);

            $total = (int) $stmt->fetchColumn();

            if ($total >= $max) {
                $this->pdo->commit();
                return false;
            }

            $insert = $this->pdo->prepare(
                'INSERT INTO rate_limits (ip, endpoint) VALUES (:ip, :endpoint)'
            );
            $insert->execute([
                ':ip' => $ip,
                ':endpoint' => $endpoint,
            ]);

            $this->pdo->commit();
            return true;
        } catch (Throwable $e) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $e;
        }
    }
}
