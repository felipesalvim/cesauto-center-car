<?php

declare(strict_types=1);

/**
 * Header do site (home e páginas internas).
 *
 * Variáveis: $e, $waUrl, $navHome, $config (opcional) / $siteName
 */

require_once dirname(__DIR__, 2) . '/helpers/logo.php';

$logo = bk_logo_asset();
$navHome = $navHome ?? true;
$navBase = $navHome ? '' : 'index.php';
$biz = $config['business'] ?? [];
$siteName = $siteName ?? ($biz['name'] ?? 'Cesauto Center Car');
$brandTag = $biz['tagline'] ?? 'Oficina · Fortaleza';
?>
<header class="site-header" data-header>
  <div class="site-header__inner">
    <a class="brand brand--logo" href="<?= $navHome ? '#topo' : 'index.php' ?>" aria-label="<?= htmlspecialchars($siteName, ENT_QUOTES, 'UTF-8') ?> — início">
      <?php if ($logo['exists']): ?>
        <img
          class="brand__logo"
          src="<?= $e($logo['path']) ?>"
          alt="<?= $e($siteName) ?>"
          width="160"
          height="48"
          decoding="async"
        >
      <?php else: ?>
        <span class="brand__mark" aria-hidden="true">
          <span class="brand__mark-text">CC</span>
        </span>
        <span class="brand__text">
          <span class="brand__name"><?= $e($siteName) ?></span>
          <span class="brand__tag"><?= $e($brandTag) ?></span>
        </span>
      <?php endif; ?>
    </a>

    <nav class="nav" id="nav-principal" aria-label="Principal" data-nav>
      <a href="<?= $e($navBase) ?>#solucoes" data-nav-link>Soluções</a>
      <a href="<?= $e($navBase) ?>#servicos" data-nav-link>Serviços</a>
      <a href="<?= $e($navBase) ?>#depoimentos" data-nav-link>Depoimentos</a>
      <a href="<?= $e($navBase) ?>#contato" data-nav-link>Local e contato</a>
    </nav>

    <div class="header-actions">
      <a class="btn btn--whatsapp btn--compact" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">WhatsApp</a>
      <button type="button" class="nav-toggle" data-nav-toggle aria-expanded="false" aria-controls="nav-principal">
        <span class="nav-toggle__bars" aria-hidden="true"></span>
        <span class="visually-hidden">Abrir menu</span>
      </button>
    </div>
  </div>
</header>
