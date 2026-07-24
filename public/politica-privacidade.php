<?php

declare(strict_types=1);

$config = require dirname(__DIR__) . '/src/bootstrap.php';
$biz = $config['business'];

$siteName = $biz['name'];
$street = $biz['street'] . ' - ' . $biz['neighborhood'];
$phoneDisplay = $biz['phone_display'];
$whatsapp = $config['whatsapp'] ?? $biz['whatsapp'];
$appUrl = $config['app_url'] !== '' ? $config['app_url'] : '';
$pageUrl = $appUrl !== '' ? $appUrl . '/politica-privacidade.php' : '';

$waDigits = preg_replace('/\D+/', '', (string) $whatsapp) ?: $biz['whatsapp'];
$waText = rawurlencode('Olá! Quero informações sobre privacidade / LGPD na ' . $siteName . '.');
$waUrl = 'https://wa.me/' . $waDigits . '?text=' . $waText;
$mapsQuery = rawurlencode($biz['address_line']);
$mapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . $mapsQuery;

$title = 'Política de Privacidade | ' . $siteName . ' — Fortaleza/CE';
$description = 'Política de Privacidade da ' . $siteName . ' em conformidade com a LGPD (Lei nº 13.709/2018). Saiba como tratamos seus dados pessoais.';
$updatedAt = '23 de julho de 2026';

$e = static function (?string $value): string {
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
};

$navHome = false;
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
  <link rel="canonical" href="<?= $e($pageUrl) ?>">
  <link rel="icon" href="../assets/img/favicon.svg" type="image/svg+xml">
  <meta property="og:type" content="website">
  <meta property="og:locale" content="pt_BR">
  <meta property="og:title" content="<?= $e($title) ?>">
  <meta property="og:description" content="<?= $e($description) ?>">
  <link rel="stylesheet" href="../assets/css/main.css">
</head>
<body class="page-legal">
  <a class="skip-link" href="#conteudo">Ir para o conteúdo</a>
  <div class="scroll-progress" data-scroll-progress aria-hidden="true"></div>

  <?php require dirname(__DIR__) . '/src/views/partials/site-header.php'; ?>

  <main id="conteudo" class="legal-page">
    <div class="section__inner legal-page__inner">
      <nav class="legal-breadcrumb" aria-label="Você está em">
        <a href="index.php">Início</a>
        <span aria-hidden="true">/</span>
        <span>Política de Privacidade</span>
      </nav>

      <header class="legal-page__header">
        <p class="eyebrow">LGPD · Lei nº 13.709/2018</p>
        <h1>Política de Privacidade</h1>
        <p class="legal-page__meta">Última atualização: <?= $e($updatedAt) ?></p>
        <p class="section__lead section__lead--wide">
          Esta Política descreve como a <strong><?= $e($siteName) ?></strong> coleta, usa, armazena, compartilha e protege dados pessoais
          no site e nos canais de atendimento (incluindo WhatsApp e formulário de orçamento/agendamento), em conformidade com a
          Lei Geral de Proteção de Dados Pessoais (LGPD).
        </p>
      </header>

      <nav class="legal-toc" aria-label="Sumário">
        <h2 class="legal-toc__title">Sumário</h2>
        <ol>
          <li><a href="#controlador">Quem é o controlador</a></li>
          <li><a href="#abrangencia">Abrangência</a></li>
          <li><a href="#dados-coletados">Dados pessoais coletados</a></li>
          <li><a href="#finalidades">Finalidades do tratamento</a></li>
          <li><a href="#bases-legais">Bases legais (LGPD)</a></li>
          <li><a href="#cookies">Cookies e tecnologias similares</a></li>
          <li><a href="#compartilhamento">Compartilhamento de dados</a></li>
          <li><a href="#transferencia">Transferência internacional</a></li>
          <li><a href="#retencao">Retenção e descarte</a></li>
          <li><a href="#seguranca">Segurança da informação</a></li>
          <li><a href="#seus-direitos">Seus direitos como titular</a></li>
          <li><a href="#criancas">Crianças e adolescentes</a></li>
          <li><a href="#decisoes">Decisões automatizadas</a></li>
          <li><a href="#alteracoes">Alterações nesta política</a></li>
          <li><a href="#contato-privacidade">Canal de privacidade</a></li>
        </ol>
      </nav>

      <article class="legal-content">
        <section id="controlador">
          <h2>1. Quem é o controlador dos dados</h2>
          <p>
            O controlador dos dados pessoais tratados por meio deste site e dos canais de contato associados é a
            <strong><?= $e($siteName) ?></strong>, oficina mecânica com atendimento em Fortaleza/CE, no endereço
            <strong><?= $e($biz['address_line']) ?></strong>.
          </p>
          <p>
            Contato principal para assuntos de privacidade: WhatsApp/telefone
            <a href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $e($phoneDisplay) ?></a>
            ou pelo formulário disponível na página inicial.
          </p>
          <p>
            Quando a razão social completa, CNPJ e e-mail do Encarregado (DPO) forem disponibilizados publicamente,
            estes dados poderão ser inseridos nesta seção sem alterar o restante desta Política.
          </p>
        </section>

        <section id="abrangencia">
          <h2>2. Abrangência</h2>
          <p>Esta Política aplica-se a:</p>
          <ul>
            <li>visitantes do site da <?= $e($siteName) ?>;</li>
            <li>pessoas que enviam dados pelo formulário de orçamento/agendamento;</li>
            <li>pessoas que entram em contato pelos canais oficiais (ex.: WhatsApp indicado no site);</li>
            <li>uso de cookies e registros técnicos necessários ao funcionamento e segurança do site, quando aplicável.</li>
          </ul>
          <p>
            Não se aplica a sites de terceiros eventualmente linkados (ex.: Google Maps ou WhatsApp Web), que possuem
            políticas próprias.
          </p>
        </section>

        <section id="dados-coletados">
          <h2>3. Dados pessoais coletados</h2>
          <h3>3.1 Dados fornecidos por você</h3>
          <ul>
            <li><strong>Identificação e contato:</strong> nome completo, telefone/WhatsApp e, se informado, e-mail;</li>
            <li><strong>Dados do veículo (opcional):</strong> modelo do carro e informações descritas na mensagem/solicitação;</li>
            <li><strong>Conteúdo da solicitação:</strong> serviço de interesse e demais informações enviadas para orçamento ou agendamento;</li>
            <li><strong>Consentimento:</strong> registro da aceitação desta Política (checkbox LGPD) no momento do envio do formulário.</li>
          </ul>

          <h3>3.2 Dados coletados automaticamente</h3>
          <ul>
            <li><strong>Dados técnicos de segurança:</strong> endereço IP, data/hora da solicitação e evidências de uso do formulário (ex.: controle de taxa de envios), para prevenção a abuso e segurança;</li>
            <li><strong>Dados de navegação:</strong> informações típicas de servidor/navegador (quando registradas pela hospedagem), como páginas acessadas, horário e user-agent.</li>
          </ul>

          <h3>3.3 Dados sensíveis</h3>
          <p>
            A <?= $e($siteName) ?> <strong>não solicita</strong> dados sensíveis (origem racial/étnica, convicção religiosa, opinião política,
            saúde, vida sexual, dados genéticos ou biométricos) para orçamento/agendamento. Pedimos que você não envie
            esse tipo de informação pelos formulários ou mensagens.
          </p>
        </section>

        <section id="finalidades">
          <h2>4. Finalidades do tratamento</h2>
          <p>Tratamos dados pessoais para:</p>
          <ul>
            <li>responder solicitações de orçamento, agendamento e dúvidas sobre serviços automotivos;</li>
            <li>entrar em contato pelos canais informados (WhatsApp, telefone e/ou e-mail);</li>
            <li>organizar o atendimento e o histórico mínimo necessário da solicitação;</li>
            <li>cumprir obrigações legais e regulatórias aplicáveis;</li>
            <li>exercer direitos em processos administrativos, judiciais ou arbitrais, quando necessário;</li>
            <li>proteger o site e os sistemas (prevenção a spam, fraude, ataques e uso abusivo do formulário);</li>
            <li>melhorar a experiência do site e a qualidade do atendimento, com base em registros agregados/estatísticos sempre que possível.</li>
          </ul>
          <p>
            <strong>Não vendemos</strong> dados pessoais. Não utilizamos seus dados para marketing de terceiros sem base legal
            adequada e, quando exigido, sem o seu consentimento.
          </p>
        </section>

        <section id="bases-legais">
          <h2>5. Bases legais (LGPD)</h2>
          <p>Conforme o art. 7º da LGPD, as principais bases legais utilizadas são:</p>
          <ul>
            <li><strong>Consentimento</strong> (art. 7º, I): quando você marca o aceite desta Política e envia o formulário;</li>
            <li><strong>Execução de procedimentos preliminares / contrato</strong> (art. 7º, V): para atender pedidos de orçamento e agendamento de serviços;</li>
            <li><strong>Legítimo interesse</strong> (art. 7º, IX): segurança do site, prevenção a abuso e melhoria operacional, observados os direitos do titular;</li>
            <li><strong>Cumprimento de obrigação legal ou regulatória</strong> (art. 7º, II): quando a retenção ou o fornecimento de dados for exigido por lei;</li>
            <li><strong>Exercício regular de direitos</strong> (art. 7º, VI): em processos e defesa de interesses legítimos da <?= $e($siteName) ?>.</li>
          </ul>
        </section>

        <section id="cookies">
          <h2>6. Cookies e tecnologias similares</h2>
          <p>
            O site pode utilizar cookies e armazenamento de sessão necessários ao funcionamento (ex.: sessão PHP para
            proteção CSRF do formulário e mensagens de retorno). Esses recursos têm finalidade técnica/segurança.
          </p>
          <p>
            Caso no futuro sejam adotados cookies analíticos ou de marketing, informaremos nesta Política e, quando exigido,
            solicitaremos consentimento por meio de banner ou mecanismo equivalente.
          </p>
          <p>
            Você pode configurar o navegador para bloquear cookies; isso pode afetar funções do site (como o envio seguro do formulário).
          </p>
        </section>

        <section id="compartilhamento">
          <h2>7. Compartilhamento de dados</h2>
          <p>Podemos compartilhar dados pessoais apenas quando necessário, com:</p>
          <ul>
            <li><strong>Prestadores de infraestrutura:</strong> hospedagem, e-mail e ferramentas técnicas que processam dados sob instrução da <?= $e($siteName) ?>;</li>
            <li><strong>Plataformas de mensagem:</strong> ao usar WhatsApp, os dados da conversa também estão sujeitos às políticas da Meta/WhatsApp;</li>
            <li><strong>Autoridades:</strong> quando houver obrigação legal, ordem judicial ou requisição de autoridade competente;</li>
            <li><strong>Assessoria jurídica/contábil:</strong> quando necessário para defesa de direitos ou cumprimento de obrigações.</li>
          </ul>
          <p>
            Exigimos de operadores e prestadores o tratamento adequado e a confidencialidade compatível com a LGPD,
            na medida do contrato e da natureza do serviço.
          </p>
        </section>

        <section id="transferencia">
          <h2>8. Transferência internacional de dados</h2>
          <p>
            Como regra, buscamos manter o tratamento no Brasil. Contudo, alguns provedores (hospedagem, CDN, WhatsApp/Meta)
            podem processar dados em outros países.
          </p>
          <p>
            Nesses casos, adotamos medidas compatíveis com a LGPD (arts. 33 a 36), como uso de fornecedores com cláusulas
            contratuais e/ou mecanismos adequados de transferência, e avaliação de risco proporcional ao tratamento.
          </p>
        </section>

        <section id="retencao">
          <h2>9. Retenção e descarte</h2>
          <p>Mantemos dados pessoais apenas pelo tempo necessário às finalidades informadas, incluindo:</p>
          <ul>
            <li>atendimento da solicitação e histórico mínimo de relacionamento comercial;</li>
            <li>prazos legais, fiscais e de exercício de direitos;</li>
            <li>registros de segurança (ex.: IP e rate limit) por período limitado à prevenção a abuso.</li>
          </ul>
          <p>
            Ao término do prazo, os dados serão eliminados ou anonimizados, salvo hipóteses legais de conservação
            (art. 16 da LGPD).
          </p>
        </section>

        <section id="seguranca">
          <h2>10. Segurança da informação</h2>
          <p>Adotamos medidas técnicas e administrativas razoáveis para proteger os dados pessoais, incluindo:</p>
          <ul>
            <li>comunicação e armazenamento com boas práticas de hospedagem;</li>
            <li>uso de consultas parametrizadas (PDO) no banco de dados;</li>
            <li>proteção CSRF em formulários;</li>
            <li>limitação de taxa de envios (rate limit) por IP/sessão;</li>
            <li>controle de acesso e segregação de credenciais (arquivo <code>.env</code> fora da pasta pública);</li>
            <li>minimização: coletamos apenas o necessário ao atendimento.</li>
          </ul>
          <p>
            Nenhum sistema é 100% seguro. Em caso de incidente relevante de segurança que possa acarretar risco ou dano
            relevante aos titulares, adotaremos as providências cabíveis, incluindo comunicação à ANPD e aos titulares,
            quando exigido pela legislação.
          </p>
        </section>

        <section id="seus-direitos">
          <h2>11. Seus direitos como titular</h2>
          <p>Nos termos do art. 18 da LGPD, você pode solicitar:</p>
          <ul>
            <li>confirmação da existência de tratamento;</li>
            <li>acesso aos dados;</li>
            <li>correção de dados incompletos, inexatos ou desatualizados;</li>
            <li>anonimização, bloqueio ou eliminação de dados desnecessários, excessivos ou tratados em desconformidade;</li>
            <li>portabilidade, observados segredos comercial e industrial e regulamentação da ANPD;</li>
            <li>eliminação dos dados tratados com base no consentimento, ressalvadas as hipóteses legais de retenção;</li>
            <li>informação sobre entidades públicas e privadas com as quais compartilhamos dados;</li>
            <li>informação sobre a possibilidade de não consentir e as consequências;</li>
            <li>revogação do consentimento;</li>
            <li>oposição a tratamento realizado com base em uma das hipóteses de dispensa de consentimento, em caso de descumprimento da LGPD.</li>
          </ul>
          <p>
            Para exercer seus direitos, utilize o canal indicado em
            <a href="#contato-privacidade">Contato de privacidade</a>. Poderemos solicitar informações para confirmar sua identidade
            antes de atender a solicitação, a fim de evitar fraudes e acessos indevidos.
          </p>
          <p>
            Você também pode apresentar reclamação à Autoridade Nacional de Proteção de Dados (ANPD), conforme a legislação vigente.
          </p>
        </section>

        <section id="criancas">
          <h2>12. Crianças e adolescentes</h2>
          <p>
            Os serviços da <?= $e($siteName) ?> destinam-se a adultos capazes de contratar ou a responsáveis legais.
            Não coletamos intencionalmente dados de crianças. Se identificarmos coleta indevida, adotaremos medidas
            para exclusão, observados deveres legais.
          </p>
        </section>

        <section id="decisoes">
          <h2>13. Decisões automatizadas</h2>
          <p>
            Não realizamos decisões unicamente automatizadas que afetem significativamente interesses do titular no
            contexto deste site (ex.: aprovação/reprovação automática de crédito). Controles técnicos como rate limit
            existem apenas para segurança e prevenção a abuso.
          </p>
        </section>

        <section id="alteracoes">
          <h2>14. Alterações nesta política</h2>
          <p>
            Esta Política pode ser atualizada para refletir mudanças legais, técnicas ou operacionais.
            A data da “Última atualização” no topo indica a versão vigente. Em mudanças relevantes, poderemos destacar
            o aviso no site ou solicitar novo consentimento quando exigido.
          </p>
        </section>

        <section id="contato-privacidade">
          <h2>15. Canal de privacidade</h2>
          <p>Para dúvidas, solicitações LGPD ou exercício de direitos:</p>
          <ul>
            <li><strong>Empresa:</strong> <?= $e($siteName) ?></li>
            <li><strong>Endereço:</strong> <?= $e($biz['address_line']) ?></li>
            <li><strong>Telefone / WhatsApp:</strong> <a href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer"><?= $e($phoneDisplay) ?></a></li>
            <li><strong>Site:</strong> <a href="index.php#contato">Formulário de contato</a></li>
          </ul>
          <p>
            Esforçamo-nos para responder em prazo razoável, compatível com a complexidade da solicitação e com a LGPD.
          </p>
        </section>
      </article>

      <p class="legal-back">
        <a class="btn btn--outline" href="index.php">Voltar ao site</a>
        <a class="btn btn--whatsapp" href="<?= $e($waUrl) ?>" target="_blank" rel="noopener noreferrer">Falar no WhatsApp</a>
      </p>
    </div>
  </main>

  <?php require dirname(__DIR__) . '/src/views/partials/site-footer.php'; ?>

  <script src="../assets/js/main.js" defer></script>
</body>
</html>
