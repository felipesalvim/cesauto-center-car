<?php

declare(strict_types=1);

final class Lead
{
    /** Serviços principais (destaque na página + select). */
    public const SERVICOS_DESTAQUE = [
        'diagnostico' => [
            'label' => 'Diagnóstico computadorizado',
            'beneficio' => 'Descobre a falha com precisão — sem achismo.',
            'descricao' => 'Leitura de códigos, testes e explicação simples do que precisa (e do que não precisa).',
            'img' => 'servico-diagnostico.jpg',
            'alt' => 'Diagnóstico técnico em veículo',
        ],
        'revisao' => [
            'label' => 'Revisão completa',
            'beneficio' => 'Checklist para rodar sem surpresa no trânsito.',
            'descricao' => 'Itens de segurança, fluidos e desgaste avaliados antes de virar problema maior.',
            'img' => 'servico-revisao.jpg',
            'alt' => 'Carro em revisão preventiva',
        ],
        'freios' => [
            'label' => 'Freios',
            'beneficio' => 'Frenagem segura: pastilhas, discos e fluido.',
            'descricao' => 'Inspeção do sistema completo para você frear com confiança em Fortaleza.',
            'img' => 'servico-freios.jpg',
            'alt' => 'Manutenção do sistema de freios',
        ],
        'suspensao' => [
            'label' => 'Suspensão',
            'beneficio' => 'Mais estabilidade, conforto e menos desgaste.',
            'descricao' => 'Amortecedores, bandejas e buchas — menos vibração e pneu durando mais.',
            'img' => 'servico-suspensao.jpg',
            'alt' => 'Serviço de suspensão automotiva',
        ],
        'motor' => [
            'label' => 'Motor',
            'beneficio' => 'Falhas, consumo alto e ruídos com método.',
            'descricao' => 'Investigamos a causa raiz antes de trocar peça — economia e resultado.',
            'img' => 'servico-motor.jpg',
            'alt' => 'Manutenção e reparo de motor',
        ],
        'eletrica' => [
            'label' => 'Elétrica automotiva',
            'beneficio' => 'Partida, bateria, alternador e sistemas elétricos.',
            'descricao' => 'Do não pega ao painel aceso: diagnóstico elétrico sem tentativa e erro.',
            'img' => 'hero-oficina.jpg',
            'alt' => 'Elétrica e eletrônica automotiva',
        ],
    ];

    /** Catálogo completo para select do formulário e lista “ver todos”. */
    public const SERVICOS = [
        'diagnostico' => 'Diagnóstico computadorizado',
        'revisao' => 'Revisão completa',
        'freios' => 'Freios',
        'suspensao' => 'Suspensão',
        'motor' => 'Motor',
        'eletrica' => 'Elétrica automotiva',
        'trocas_preventivas' => 'Trocas preventivas (óleo e filtros)',
        'injecao' => 'Injeção eletrônica',
        'embreagem' => 'Embreagem',
        'cambio' => 'Câmbio (manual / automático)',
        'ar_condicionado' => 'Ar-condicionado automotivo',
        'alinhamento' => 'Alinhamento e balanceamento',
        'correia' => 'Correia dentada / sincronismo',
        'escapamento' => 'Escapamento',
        'direcao' => 'Direção hidráulica / elétrica',
        'radiador' => 'Radiador e sistema de arrefecimento',
        'ignicao' => 'Velas, bobinas e ignição',
        'bateria' => 'Bateria e alternador',
        'higienizacao' => 'Higienização de ar / cabine',
        'outro' => 'Outro / não sei informar',
    ];

    public function __construct(private PDO $pdo)
    {
    }

    /**
     * @param array{
     *   nome: string,
     *   email: ?string,
     *   telefone: string,
     *   modelo_carro: ?string,
     *   servico_interesse: string,
     *   mensagem: ?string,
     *   lgpd_consent: int,
     *   ip: string
     * } $data
     */
    public function create(array $data): int
    {
        $sql = 'INSERT INTO leads
            (nome, email, telefone, modelo_carro, servico_interesse, mensagem, lgpd_consent, ip)
            VALUES
            (:nome, :email, :telefone, :modelo_carro, :servico_interesse, :mensagem, :lgpd_consent, :ip)';

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nome' => $data['nome'],
            ':email' => $data['email'],
            ':telefone' => $data['telefone'],
            ':modelo_carro' => $data['modelo_carro'],
            ':servico_interesse' => $data['servico_interesse'],
            ':mensagem' => $data['mensagem'],
            ':lgpd_consent' => $data['lgpd_consent'],
            ':ip' => $data['ip'],
        ]);

        return (int) $this->pdo->lastInsertId();
    }
}
