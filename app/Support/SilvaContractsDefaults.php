<?php

namespace App\Support;

class SilvaContractsDefaults
{
    public static function data(): array
    {
        return [
            'intro' => [
                'kicker' => 'Yasal',
                'title' => 'Sözleşmeler',
                'aside' => 'Gizlilik, aydınlatma, çerez, güvenlik ve KVKK metinleri. Her sözleşme ayrı sayfada yer alır.',
            ],
            'items' => collect(\App\Support\SilvaLegalDefaults::types())
                ->map(fn (array $meta, string $type) => [
                    'type' => $type,
                    'number' => $meta['number'],
                    'label' => $meta['name'],
                ])
                ->values()
                ->all(),
        ];
    }
}
