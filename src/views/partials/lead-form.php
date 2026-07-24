<?php

declare(strict_types=1);

/**
 * Formulário de leads (CSRF + LGPD) — sem wrapper de seção.
 */

if (!isset($config) || !is_array($config)) {
    $config = require dirname(__DIR__, 2) . '/bootstrap.php';
}

$csrfToken = Csrf::token();
$servicos = Lead::SERVICOS;
$old = $old ?? ($_SESSION['form_old'] ?? []);
$flash = $flash ?? ($_SESSION['flash'] ?? null);
unset($_SESSION['flash']);

$e = static function (?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$phoneDisplay = $old['telefone'] ?? '';
if ($phoneDisplay !== '' && ctype_digit($phoneDisplay)) {
    if (strlen($phoneDisplay) === 11) {
        $phoneDisplay = sprintf(
            '(%s) %s-%s',
            substr($phoneDisplay, 0, 2),
            substr($phoneDisplay, 2, 5),
            substr($phoneDisplay, 7)
        );
    } elseif (strlen($phoneDisplay) === 10) {
        $phoneDisplay = sprintf(
            '(%s) %s-%s',
            substr($phoneDisplay, 0, 2),
            substr($phoneDisplay, 2, 4),
            substr($phoneDisplay, 6)
        );
    }
}
?>
<div class="lead-form" id="formulario-orcamento">
  <?php if (is_array($flash) && isset($flash['message'])): ?>
    <div class="alert alert--<?= $e($flash['type'] ?? 'info') ?>" role="status" tabindex="-1" data-flash-alert>
      <?= $e((string) $flash['message']) ?>
    </div>
  <?php endif; ?>

  <form class="lead-form__form" method="post" action="enviar-lead.php" novalidate data-lead-form>
    <input type="hidden" name="csrf_token" value="<?= $e($csrfToken) ?>">

    <div class="form-field">
      <label for="nome">Nome completo</label>
      <input type="text" id="nome" name="nome" maxlength="120" required autocomplete="name"
             placeholder="Seu nome"
             value="<?= $e($old['nome'] ?? '') ?>">
    </div>

    <div class="form-field">
      <label for="telefone">WhatsApp</label>
      <input type="tel" id="telefone" name="telefone" maxlength="16" required autocomplete="tel"
             inputmode="tel" placeholder="(85) 99999-9999"
             data-phone-mask
             value="<?= $e($phoneDisplay) ?>">
      <span class="field-hint">Usaremos este número para retornar o orçamento</span>
    </div>

    <div class="form-field">
      <label for="servico_interesse">Serviço de interesse</label>
      <select id="servico_interesse" name="servico_interesse" required data-service-select>
        <option value="">Selecione…</option>
        <?php foreach ($servicos as $value => $label): ?>
          <option value="<?= $e($value) ?>"
            <?= (($old['servico_interesse'] ?? '') === $value) ? 'selected' : '' ?>>
            <?= $e($label) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-row">
      <div class="form-field">
        <label for="modelo_carro">Modelo do carro <span class="optional">(opcional)</span></label>
        <input type="text" id="modelo_carro" name="modelo_carro" maxlength="80" autocomplete="off"
               placeholder="Ex.: HB20 2019"
               value="<?= $e($old['modelo_carro'] ?? '') ?>">
      </div>

      <div class="form-field">
        <label for="email">E-mail <span class="optional">(opcional)</span></label>
        <input type="email" id="email" name="email" maxlength="180" autocomplete="email"
               placeholder="seu@email.com"
               value="<?= $e($old['email'] ?? '') ?>">
      </div>
    </div>

    <div class="form-field form-field--checkbox">
      <input type="checkbox" id="lgpd_consent" name="lgpd_consent" value="1" required>
      <label for="lgpd_consent">
        Li e aceito a
        <a href="politica-privacidade.php">política de privacidade</a>
        (LGPD) para contato sobre orçamento/agendamento.
      </label>
    </div>

    <button type="submit" class="btn btn--secondary btn--block" data-submit-btn>
      <span data-submit-label>Quero meu orçamento</span>
      <span data-submit-loading hidden>Enviando…</span>
    </button>
  </form>
</div>
