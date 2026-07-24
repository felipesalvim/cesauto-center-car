<?php

declare(strict_types=1);

/**
 * Footer profissional com slot de logo.
 *
 * Variáveis: $e, $waUrl, $mapsUrl, $config / $biz / $siteName / $phoneDisplay
 */

require_once dirname(__DIR__, 2) . '/helpers/logo.php';

$logo = bk_logo_asset();
$biz = $biz ?? ($config['business'] ?? []);
$siteName = $siteName ?? ($biz['name'] ?? 'Cesauto Center Car');
$phoneDisplay = $phoneDisplay ?? ($biz['phone_display'] ?? '(85) 98619-1000');
$addressShort = $biz['address_short'] ?? ($street ?? 'R. Nogueira Acioli, 981 — Centro, Fortaleza/CE');
$year = (int) date('Y');
?>
<footer class="site-footer">
  <div class="site-footer__inner">
    <div class="site-footer__grid">
      <div class="site-footer__brand">
        <a class="brand brand--logo brand--footer" href="index.php" aria-label="<?= $e($siteName) ?> — início">
          <?php if ($logo['exists']): ?>
            <img
              class="brand__logo"
              src="<?= $e($logo['path']) ?>"
              alt="<?= $e($siteName) ?>"
              width="160"
              height="48"
              loading="lazy"
              decoding="async"
            >
          <?php else: ?>
            <span class="brand__mark" aria-hidden="true">
              <span class="brand__mark-text">CC</span>
            </span>
            <span class="brand__text">
              <span class="brand__name"><?= $e($siteName) ?></span>
              <span class="brand__tag">Oficina mecânica</span>
            </span>
          <?php endif; ?>
        </a>
        <p class="site-footer__about">
          Oficina mecânica em Fortaleza com nota <?= $e($biz['rating_label'] ?? '4,25') ?> no Google.
          Diagnóstico claro, orçamento transparente e atendimento no Centro.
        </p>
      </div>

      <div class="site-footer__col">
        <h2 class="site-footer__heading">Navegação</h2>
        <ul class="site-footer__links">
          <li><a href="index.php#solucoes">Soluções</a></li>
          <li><a href="index.php#servicos">Serviços</a></li>
          <li><a href="index.php#depoimentos">Depoimentos</a></li>
          <li><a href="index.php#contato">Local e contato</a></li>
        </ul>
      </div>

      <div class="site-footer__col">
        <h2 class="site-footer__heading">Contato</h2>
        <ul class="site-footer__links">
          <li><?= $e($biz['address_line'] ?? $addressShort) ?></li>
          <li>
            <a href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">WhatsApp <?= $e($phoneDisplay) ?></a>
          </li>
          <li>
            <a href="tel:<?= $e($biz['phone_e164'] ?? '+5585986191000') ?>">Ligar <?= $e($phoneDisplay) ?></a>
          </li>
          <li>
            <a href="<?= $e($mapsUrl) ?>" target="_blank" rel="noopener noreferrer">Como chegar</a>
          </li>
          <li>Seg–sex 07:30–17:00 · Sáb 07:30–12:00 · Dom fechado</li>
        </ul>
      </div>

      <div class="site-footer__col">
        <h2 class="site-footer__heading">Legal</h2>
        <ul class="site-footer__links">
          <li><a href="politica-privacidade.php">Política de privacidade</a></li>
          <li><a href="politica-privacidade.php#seus-direitos">Seus direitos (LGPD)</a></li>
          <li><a href="index.php#contato">Falar conosco</a></li>
        </ul>
      </div>
    </div>

    <div class="site-footer__bottom">
      <p>&copy; <?= $year ?> <?= $e($siteName) ?>. Todos os direitos reservados.</p>
      <p class="site-footer__note">CNPJ e razão social podem ser incluídos aqui quando disponíveis.</p>
    </div>
  </div>
</footer>
