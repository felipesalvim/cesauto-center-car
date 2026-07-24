<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/src/bootstrap.php';
$biz = $config['business'];

$siteName = $biz['name'];
$city = $biz['city'];
$region = $biz['region'];
$street = $biz['street'];
$whatsapp = $config['whatsapp'] ?? $biz['whatsapp'];
$phoneDisplay = $biz['phone_display'];
$appUrl = $config['app_url'] !== '' ? $config['app_url'] : '';
$pageUrl = $appUrl !== '' ? $appUrl . '/index.php' : '';
$assetsBase = ($appUrl !== '' ? dirname($appUrl) : '..') . '/assets';
$waDigits = preg_replace('/\D+/', '', (string) $whatsapp) ?: $biz['whatsapp'];

$waText = rawurlencode('Olá! Quero agendar ou pedir orçamento na ' . $siteName . '.');
$waUrl = 'https://wa.me/' . $waDigits . '?text=' . $waText;
$mapsQuery = rawurlencode($biz['address_line']);
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;
$mapsEmbed = 'https://maps.google.com/maps?q=' . $mapsQuery . '&z=16&output=embed';

$title = $siteName . ' | Oficina mecânica em Fortaleza — revisão, diagnóstico e reparos';
$description = $siteName . ' — oficina mecânica em Fortaleza/CE. Nota ' . $biz['rating_label'] . ' no Google. '
    . $biz['address_line'] . ' · Tel. ' . $phoneDisplay . '. Diagnóstico claro, orçamento transparente e garantia no serviço.';

$servicosDestaque = Lead::SERVICOS_DESTAQUE;
$servicosTodos = Lead::SERVICOS;
$destaqueKeys = array_keys($servicosDestaque);
$servicosExtras = array_filter(
    $servicosTodos,
    static fn(string $key): bool => !in_array($key, $destaqueKeys, true) && $key !== 'outro',
    ARRAY_FILTER_USE_KEY
);

$openingHours = [];
foreach ($biz['hours_schema'] as $slot) {
    $openingHours[] = [
        '@type' => 'OpeningHoursSpecification',
        'dayOfWeek' => $slot['dayOfWeek'],
        'opens' => $slot['opens'],
        'closes' => $slot['closes'],
    ];
}

$jsonLdServices = [];
foreach ($servicosTodos as $label) {
    if ($label === 'Outro / não sei informar') {
        continue;
    }
    $jsonLdServices[] = [
        '@type' => 'Offer',
        'itemOffered' => [
            '@type' => 'Service',
            'name' => $label,
            'areaServed' => 'Fortaleza',
        ],
    ];
}

$jsonLd = [
    '@context' => 'https://schema.org',
    '@graph' => [
        [
            '@type' => 'AutoRepair',
            '@id' => ($pageUrl !== '' ? $pageUrl : '#') . '#localbusiness',
            'name' => $siteName,
            'description' => $description,
            'url' => $pageUrl !== '' ? $pageUrl : null,
            'telephone' => $biz['phone_e164'],
            'image' => $assetsBase . '/img/hero-oficina.jpg',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => $biz['street'] . ' - ' . $biz['neighborhood'],
                'addressLocality' => $city,
                'addressRegion' => $region,
                'postalCode' => $biz['postal_code'],
                'addressCountry' => 'BR',
            ],
            'geo' => [
                '@type' => 'GeoCoordinates',
                'addressCountry' => 'BR',
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Fortaleza',
            ],
            'openingHoursSpecification' => $openingHours,
            'aggregateRating' => [
                '@type' => 'AggregateRating',
                'ratingValue' => $biz['rating_value'],
                'bestRating' => 5,
                'worstRating' => 1,
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Serviços automotivos',
                'itemListElement' => $jsonLdServices,
            ],
        ],
        [
            '@type' => 'Service',
            'name' => 'Revisão completa',
            'provider' => ['@id' => ($pageUrl !== '' ? $pageUrl : '#') . '#localbusiness'],
            'areaServed' => 'Fortaleza',
            'serviceType' => 'Manutenção preventiva',
        ],
        [
            '@type' => 'Service',
            'name' => 'Diagnóstico computadorizado',
            'provider' => ['@id' => ($pageUrl !== '' ? $pageUrl : '#') . '#localbusiness'],
            'areaServed' => 'Fortaleza',
            'serviceType' => 'Diagnóstico automotivo',
        ],
    ],
];

// Remove geo vazio (sem lat/lng inventados)
unset($jsonLd['@graph'][0]['geo']);

$jsonLd = json_decode(
    (string) json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
    true
);

$e = static function (?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$waForService = static function (string $label) use ($waDigits, $siteName): string {
    $text = rawurlencode('Olá! Quero orçamento de: ' . $label . ' na ' . $siteName . '.');
    return 'https://wa.me/' . $waDigits . '?text=' . $text;
};

$flash = $_SESSION['flash'] ?? null;
$old = $_SESSION['form_old'] ?? [];

$dores = [
    [
        'n' => '01',
        'icon' => 'scanner',
        'img' => 'servico-diagnostico.jpg',
        'dor' => 'Luz no painel ou barulho sem explicação',
        'solucao' => 'Scanner + laudo claro do que falha e do que pode esperar.',
    ],
    [
        'n' => '02',
        'icon' => 'orcamento',
        'img' => 'oficina-equipe.jpg',
        'dor' => 'Medo de pagar serviço desnecessário',
        'solucao' => 'Orçamento explicado antes — você aprova só o necessário.',
    ],
    [
        'n' => '03',
        'icon' => 'freio',
        'img' => 'servico-freios.jpg',
        'dor' => 'Freio mole ou carro inseguro no trânsito',
        'solucao' => 'Freios, suspensão e revisão com peças de qualidade.',
    ],
    [
        'n' => '04',
        'icon' => 'garantia',
        'img' => 'servico-revisao.jpg',
        'dor' => 'Retrabalho e falta de garantia',
        'solucao' => 'Garantia no serviço + atendimento com nota ' . $biz['rating_label'] . ' no Google.',
    ],
];

$depoimentos = $biz['reviews'];

$iconSvg = static function (string $name): string {
    $icons = [
        'scanner' => '<svg viewBox="0 0 48 48" aria-hidden="true"><rect x="8" y="10" width="32" height="22" rx="3" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M14 40h20M24 32v8" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M16 18h16M16 24h10" stroke="currentColor" stroke-width="2.5"/></svg>',
        'orcamento' => '<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="14" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M24 16v16M19 20h7.5a3.5 3.5 0 010 7H19" fill="none" stroke="currentColor" stroke-width="2.5"/></svg>',
        'freio' => '<svg viewBox="0 0 48 48" aria-hidden="true"><circle cx="24" cy="24" r="14" fill="none" stroke="currentColor" stroke-width="2.5"/><circle cx="24" cy="24" r="5" fill="currentColor"/><path d="M24 10v4M24 34v4M10 24h4M34 24h4" stroke="currentColor" stroke-width="2.5"/></svg>',
        'garantia' => '<svg viewBox="0 0 48 48" aria-hidden="true"><path d="M24 8l14 6v10c0 9-6 15-14 18-8-3-14-9-14-18V14l14-6z" fill="none" stroke="currentColor" stroke-width="2.5"/><path d="M17 24l5 5 9-10" fill="none" stroke="currentColor" stroke-width="2.5"/></svg>',
    ];
    return $icons[$name] ?? $icons['scanner'];
};
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $e($title) ?></title>
  <meta name="description" content="<?= $e($description) ?>">
  <meta name="robots" content="index,follow">
  <meta name="theme-color" content="#128c7e">
  <link rel="canonical" href="<?= $e($pageUrl !== '' ? $pageUrl : '') ?>">
  <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="pt_BR">
  <meta property="og:title" content="<?= $e($title) ?>">
  <meta property="og:description" content="<?= $e($description) ?>">
  <meta property="og:url" content="<?= $e($pageUrl) ?>">
  <meta property="og:site_name" content="<?= $e($siteName) ?>">
  <meta property="og:image" content="<?= $e($assetsBase . '/img/hero-oficina.jpg') ?>">

  <link rel="stylesheet" href="../assets/css/main.css">

  <script type="application/ld+json"><?= json_encode($jsonLd, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP) ?></script>
</head>
<body>
  <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>

  <div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>

  <?php
  $navHome = true;
  require dirname(__DIR__) . '/src/views/partials/site-header.php';
  ?>

  <main id="conteudo">
    <section class="hero" id="topo" aria-labelledby="hero-titulo">
      <div class="hero__media">
        <img
          src="../assets/img/hero-oficina.jpg"
          alt="Equipe técnica da <?= $e($siteName) ?> diagnosticando veículo na oficina"
          width="1800"
          height="1200"
          fetchpriority="high"
          decoding="async"
        >
      </div>
      <div class="hero__content">
        <p class="hero__brand"><?= $e($siteName) ?></p>
        <p class="hero__local">Oficina mecânica em Fortaleza · <?= $e($biz['address_short']) ?></p>
        <p class="hero__rating" aria-label="Avaliação <?= $e($biz['rating_label']) ?> no Google">
          <span class="hero__stars" aria-hidden="true">★★★★☆</span>
          <strong><?= $e($biz['rating_label']) ?></strong>
          <span>avaliações no Google</span>
        </p>
        <h1 id="hero-titulo">Diagnóstico claro. Orçamento antes. Garantia no serviço.</h1>
        <p class="hero__lead">Seu carro com problema? A gente identifica a causa, explica o que precisa e executa com método — sem enrolação.</p>
        <div class="hero__cta">
          <a class="btn btn--whatsapp btn--lg" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">Agendar pelo WhatsApp</a>
          <a class="btn btn--ghost" href="#servicos">Ver serviços</a>
        </div>
      </div>
    </section>

    <section class="trust-bar" aria-label="Diferenciais da <?= $e($siteName) ?>" data-reveal>
      <div class="section__inner trust-bar__inner">
        <div class="trust-bar__item" data-reveal-child>
          <strong><?= $e($biz['rating_label']) ?> ★</strong>
          <span>avaliações no Google</span>
        </div>
        <div class="trust-bar__item" data-reveal-child>
          <strong>Centro</strong>
          <span>R. Nogueira Acioli, 981</span>
        </div>
        <div class="trust-bar__item" data-reveal-child>
          <strong>Seg–sex 07:30–17:00</strong>
          <span>Sábado até 12:00</span>
        </div>
        <div class="trust-bar__item" data-reveal-child>
          <strong><?= $e($phoneDisplay) ?></strong>
          <span>Telefone / WhatsApp</span>
        </div>
      </div>
    </section>

    <section class="section section--pas" id="problema" aria-labelledby="pas-titulo" data-reveal>
      <div class="section__inner">
        <p class="eyebrow">O problema</p>
        <h2 id="pas-titulo">Essas dores não precisam virar prejuízo</h2>
        <p class="section__lead">Adiar manutenção ou cair em oficina sem critério custa mais caro — e coloca você em risco no trânsito de Fortaleza.</p>
        <div class="pain-strip" role="list">
          <p role="listitem" data-reveal-child>Barulho estranho</p>
          <p role="listitem" data-reveal-child>Luz de injeção</p>
          <p role="listitem" data-reveal-child>Freio falhando</p>
          <p role="listitem" data-reveal-child>Orçamento “misterioso”</p>
          <p role="listitem" data-reveal-child>Medo de retrabalho</p>
        </div>
      </div>
    </section>

    <section class="section section--solutions" id="solucoes" aria-labelledby="solucoes-titulo" data-reveal>
      <div class="section__inner">
        <div class="split">
          <div class="split__text">
            <p class="eyebrow">A solução <?= $e($siteName) ?></p>
            <h2 id="solucoes-titulo">Cada dor com uma resposta objetiva</h2>
            <p class="section__lead">Mostramos o problema, a solução e o valor. Você decide com segurança.</p>
          </div>
          <figure class="split__media">
            <img
              src="../assets/img/oficina-equipe.jpg"
              alt="Mecânico realizando serviço em ambiente de oficina profissional"
              width="1400"
              height="933"
              loading="lazy"
              decoding="async"
            >
          </figure>
        </div>

        <div class="dor-solucao">
          <?php foreach ($dores as $i => $item): ?>
            <article class="dor-solucao__item" data-reveal-child style="--delay: <?= (int) $i * 80 ?>ms">
              <div class="dor-solucao__visual">
                <img src="../assets/img/<?= $e($item['img']) ?>" alt="" width="160" height="120" loading="lazy" decoding="async">
                <span class="dor-solucao__icon"><?= $iconSvg($item['icon']) ?></span>
              </div>
              <div class="dor-solucao__content">
                <span class="dor-solucao__n" aria-hidden="true"><?= $e($item['n']) ?></span>
                <div class="dor-solucao__dor">
                  <span class="label label--dor">Dor</span>
                  <p><?= $e($item['dor']) ?></p>
                </div>
                <div class="dor-solucao__sol">
                  <span class="label label--sol">Solução</span>
                  <p><?= $e($item['solucao']) ?></p>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <div class="solutions-cta">
          <a class="btn btn--whatsapp btn--lg" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">Falar com especialista</a>
        </div>
      </div>
    </section>

    <section class="section" id="servicos" aria-labelledby="servicos-titulo" data-reveal>
      <div class="section__inner">
        <h2 id="servicos-titulo">Serviços que resolvem o dia a dia do seu carro</h2>
        <p class="section__lead">Seis frentes principais — escolha o serviço e peça orçamento direto.</p>

        <div class="service-grid">
          <?php foreach ($servicosDestaque as $key => $svc): ?>
            <article class="service-card" data-reveal-child>
              <div class="service-card__media">
                <img
                  src="../assets/img/<?= $e($svc['img']) ?>"
                  alt="<?= $e($svc['alt']) ?>"
                  width="1000"
                  height="667"
                  loading="lazy"
                  decoding="async"
                >
              </div>
              <div class="service-card__body">
                <h3><?= $e($svc['label']) ?></h3>
                <p class="service-card__beneficio"><?= $e($svc['beneficio']) ?></p>
                <p class="service-card__desc"><?= $e($svc['descricao']) ?></p>
                <div class="service-card__actions">
                  <a class="btn btn--whatsapp btn--sm" href="<?= $e($waForService($svc['label'])) ?>" target="_blank" rel="noopener noreferrer">
                    WhatsApp
                  </a>
                  <a class="btn btn--outline btn--sm" href="#contato" data-select-service="<?= $e($key) ?>">
                    Pedir no site
                  </a>
                </div>
              </div>
            </article>
          <?php endforeach; ?>
        </div>

        <details class="more-services">
          <summary>Ver todos os serviços da oficina</summary>
          <ul class="more-services__list">
            <?php foreach ($servicosExtras as $key => $label): ?>
              <li>
                <a href="#contato" data-select-service="<?= $e($key) ?>"><?= $e($label) ?></a>
              </li>
            <?php endforeach; ?>
          </ul>
        </details>
      </div>
    </section>

    <section class="section section--proof" id="prova" aria-labelledby="prova-titulo" data-reveal>
      <div class="section__inner split split--reverse">
        <figure class="split__media">
          <img
            src="../assets/img/oficina-equipe.jpg"
            alt="Ambiente de oficina <?= $e($siteName) ?> em Fortaleza"
            width="1400"
            height="933"
            loading="lazy"
            decoding="async"
          >
        </figure>
        <div class="split__text">
          <h2 id="prova-titulo">Por que confiar na <?= $e($siteName) ?></h2>
          <ul class="proof">
            <li data-reveal-child><strong>Nota <?= $e($biz['rating_label']) ?></strong> nas avaliações do Google</li>
            <li data-reveal-child><strong>Atendimento ágil</strong> — clientes destacam atendimento sem fila</li>
            <li data-reveal-child><strong>Endereço fixo no Centro:</strong> <?= $e($biz['address_short']) ?></li>
            <li data-reveal-child><strong>Horário claro:</strong> seg–sex 07:30–17:00 · sáb 07:30–12:00</li>
            <li data-reveal-child><strong>Telefone:</strong> <a href="tel:<?= $e($biz['phone_e164']) ?>"><?= $e($phoneDisplay) ?></a></li>
          </ul>
        </div>
      </div>
    </section>

    <section class="section section--testimonials" id="depoimentos" aria-labelledby="depoimentos-titulo" data-reveal>
      <div class="section__inner">
        <p class="eyebrow">Prova social · Google</p>
        <h2 id="depoimentos-titulo">O que dizem no Google sobre a <?= $e($siteName) ?></h2>
        <p class="section__lead">Nota <strong><?= $e($biz['rating_label']) ?></strong> nas avaliações do Google · oficina mecânica em Fortaleza, Ceará.</p>
        <div class="testimonials">
          <?php foreach ($depoimentos as $dep): ?>
            <blockquote class="testimonial" data-reveal-child>
              <div class="testimonial__top">
                <span class="testimonial__avatar" aria-hidden="true"><?= $e($dep['iniciais']) ?></span>
                <div class="testimonial__meta">
                  <strong><?= $e($dep['nome']) ?></strong>
                  <span><?= $e($dep['fonte'] ?? 'Google') ?> · Fortaleza</span>
                  <span class="testimonial__stars" aria-label="<?= (int) $dep['nota'] ?> de 5 estrelas">
                    <?php for ($s = 0; $s < (int) $dep['nota']; $s++): ?>★<?php endfor; ?>
                  </span>
                </div>
              </div>
              <p>“<?= $e($dep['texto']) ?>”</p>
            </blockquote>
          <?php endforeach; ?>
        </div>
        <p class="testimonials__note">Comentários públicos do Google referentes à <?= $e($siteName) ?>.</p>
      </div>
    </section>

    <section class="section" id="como-funciona" aria-labelledby="como-titulo" data-reveal>
      <div class="section__inner">
        <h2 id="como-titulo">Do contato à solução — em 3 passos</h2>
        <ol class="steps steps--cards">
          <li data-reveal-child><strong>Agendamento</strong> — WhatsApp ou formulário com o sintoma do carro.</li>
          <li data-reveal-child><strong>Diagnóstico</strong> — avaliamos, explicamos a causa e o orçamento antes do reparo.</li>
          <li data-reveal-child><strong>Entrega</strong> — serviço com peças adequadas e garantia de 90 dias.</li>
        </ol>
      </div>
    </section>

    <section class="section section--contact" id="contato" aria-labelledby="contato-titulo" data-reveal>
      <div class="section__inner">
        <p class="eyebrow">Local e contato</p>
        <h2 id="contato-titulo">Fale com a <?= $e($siteName) ?> em Fortaleza</h2>
        <p class="section__lead section__lead--wide">WhatsApp ou telefone <?= $e($phoneDisplay) ?>. Prefere deixar os dados? Use o formulário ao lado.</p>

        <div class="contact-grid">
          <aside class="contact-info">
            <h3>Dados da oficina</h3>
            <p class="contact-info__hint">Seg–sex 07:30–17:00 · Sáb 07:30–12:00 · Dom fechado</p>
            <ul class="local-info">
              <li>
                <span class="local-info__icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7zm0 9.5A2.5 2.5 0 1 1 12 6a2.5 2.5 0 0 1 0 5.5z"/></svg>
                </span>
                <div>
                  <strong>Endereço</strong><br>
                  <?= $e($biz['address_line']) ?>
                </div>
              </li>
              <li>
                <span class="local-info__icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M20 2H4a2 2 0 0 0-2 2v18l4-4h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm-2 10H6v-2h12v2zm0-3H6V7h12v2z"/></svg>
                </span>
                <div>
                  <strong>Telefone / WhatsApp</strong><br>
                  <a href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $e($phoneDisplay) ?></a>
                  · <a href="tel:<?= $e($biz['phone_e164']) ?>">Ligar</a>
                </div>
              </li>
              <li>
                <span class="local-info__icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M18.92 6.01C18.72 5.42 18.16 5 17.5 5h-11c-.66 0-1.21.42-1.42 1.01L3 12v8a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-1h12v1a1 1 0 0 0 1 1h1a1 1 0 0 0 1-1v-8l-2.08-5.99zM6.5 16a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zm11 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3zM5 11l1.5-4.5h11L19 11H5z"/></svg>
                </span>
                <div><strong>Área</strong><br>Fortaleza e região · Centro</div>
              </li>
              <li>
                <span class="local-info__icon" aria-hidden="true">
                  <svg viewBox="0 0 24 24" width="18" height="18"><path fill="currentColor" d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
                </span>
                <div>
                  <strong>Horário de funcionamento</strong><br>
                  <?php foreach ($biz['hours'] as $h): ?>
                    <?= $e($h['days']) ?>:
                    <?= $h['closed'] ? 'Fechado' : $e($h['opens'] . '–' . $h['closes']) ?><br>
                  <?php endforeach; ?>
                </div>
              </li>
            </ul>
            <div class="contact-info__actions">
              <a class="btn btn--whatsapp btn--lg" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">Falar via WhatsApp</a>
              <a class="btn btn--outline" href="<?= $e($mapsUrl) ?>" target="_blank" rel="noopener noreferrer">Abrir no Maps</a>
            </div>
          </aside>

          <div class="contact-form-wrap">
            <h3>Peça seu orçamento</h3>
            <p class="contact-form-wrap__hint">Leva menos de 1 minuto. Sem compromisso.</p>
            <?php require dirname(__DIR__) . '/src/views/partials/lead-form.php'; ?>
          </div>
        </div>
      </div>

      <div class="local-map local-map--full" data-reveal>
        <iframe
          title="Mapa da <?= $e($siteName) ?> — <?= $e($biz['address_line']) ?>"
          src="<?= $e($mapsEmbed) ?>"
          loading="lazy"
          referrerpolicy="no-referrer-when-downgrade"
          allowfullscreen
        ></iframe>
      </div>
    </section>
  </main>

  <?php require dirname(__DIR__) . '/src/views/partials/site-footer.php'; ?>

  <a class="whatsapp-fab" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer" aria-label="Abrir WhatsApp da <?= $e($siteName) ?>" data-wa-fab>
    <svg viewBox="0 0 32 32" width="22" height="22" aria-hidden="true" focusable="false">
      <path fill="currentColor" d="M16.01 3C9.4 3 4.02 8.37 4.02 14.98c0 2.1.55 4.14 1.6 5.95L4 29l8.27-1.57a12 12 0 0 0 3.74.59h.01c6.61 0 11.98-5.37 11.98-11.98C28 8.37 22.62 3 16.01 3zm6.97 16.93c-.29.82-1.7 1.5-2.38 1.6-.61.09-1.38.13-2.23-.14-.52-.16-1.18-.38-2.03-.74-3.57-1.54-5.9-5.14-6.08-5.38-.17-.24-1.43-1.9-1.43-3.63s.9-2.57 1.22-2.92c.32-.35.7-.44.93-.44h.67c.22 0 .5-.08.79.6.29.7.99 2.42 1.08 2.6.09.17.15.38.03.61-.12.24-.18.38-.36.59-.17.2-.37.45-.53.61-.17.17-.35.35-.15.68.2.32.9 1.48 1.93 2.4 1.33 1.18 2.45 1.55 2.8 1.72.35.17.55.15.75-.09.2-.24.87-1.02 1.1-1.37.23-.35.47-.29.79-.17.32.12 2.05.97 2.4 1.14.35.18.58.26.67.41.08.15.08.88-.21 1.7z"/>
    </svg>
    <span>WhatsApp</span>
  </a>

  <button type="button" class="back-top" data-back-top aria-label="Voltar ao topo" hidden>
    ↑
  </button>

  <script src="../assets/js/main.js" defer></script>
</body>
</html>
