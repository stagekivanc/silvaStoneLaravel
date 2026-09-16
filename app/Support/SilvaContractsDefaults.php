<?php

namespace App\Support;

use App\Support\Concerns\HasLocalizedDefaults;

class SilvaContractsDefaults
{
    use HasLocalizedDefaults;

    protected static function baseData(): array
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
