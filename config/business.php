<?php

declare(strict_types=1);

/**
 * Dados oficiais da empresa (Google Business / operação).
 * Fonte: Cesauto Center Car — Fortaleza/CE
 */

return [
    'name' => 'Cesauto Center Car',
    'tagline' => 'Oficina mecânica em Fortaleza',
    'street' => 'R. Nogueira Acioli, 981',
    'neighborhood' => 'Centro',
    'city' => 'Fortaleza',
    'region' => 'CE',
    'postal_code' => '60110-140',
    'country' => 'BR',
    'phone_display' => '(85) 98619-1000',
    'phone_e164' => '+5585986191000',
    'whatsapp' => '5585986191000',
    'rating_value' => 4.25,
    'rating_label' => '4,25',
    'rating_source' => 'Google',
    'address_line' => 'R. Nogueira Acioli, 981 - Centro, Fortaleza - CE, 60110-140',
    'address_short' => 'R. Nogueira Acioli, 981 — Centro, Fortaleza/CE',
    'hours' => [
        ['days' => 'Segunda a sexta', 'opens' => '07:30', 'closes' => '17:00', 'closed' => false],
        ['days' => 'Sábado', 'opens' => '07:30', 'closes' => '12:00', 'closed' => false],
        ['days' => 'Domingo', 'opens' => null, 'closes' => null, 'closed' => true],
    ],
    'hours_schema' => [
        ['dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'], 'opens' => '07:30', 'closes' => '17:00'],
        ['dayOfWeek' => 'Saturday', 'opens' => '07:30', 'closes' => '12:00'],
    ],
    'reviews' => [
        [
            'nome' => 'Antonio Carlos',
            'bairro' => 'Google',
            'iniciais' => 'AC',
            'nota' => 5,
            'texto' => 'Só tinha uma pessoa no atendimento, sem fila nenhuma.',
            'fonte' => 'Google',
        ],
        [
            'nome' => 'Thiago Mesquita',
            'bairro' => 'Google',
            'iniciais' => 'TM',
            'nota' => 5,
            'texto' => 'Bernardo se garante demais!',
            'fonte' => 'Google',
        ],
    ],
];
