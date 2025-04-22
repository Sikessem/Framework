<?php

namespace Sikessem\Concerns;

trait FormatsUserIdentity
{
    public function formattedIdentity(): string
    {
        $identityParts = [];

        if (! empty($this->name)) {
            $identityParts[] = "👤 {$this->name}";
        }

        if (! empty($this->email)) {
            $identityParts[] = " ✉️ <{$this->email}>";
        }

        $info = [
            '📞' => $this->phone_number ?? (($phones = $this->phones) ? implode(', ', $phones->pluck('number')->toArray()) : null),
            '🆔' => $this->id_card_number ?? null,
            '🛂' => $this->passport_number ?? null,
            '🏢' => $this->company ?? null,
            '🌐' => $this->website ?? null,
        ];

        foreach ($info as $icon => $value) {
            if (! empty($value)) {
                $identityParts[] = " | {$icon} {$value}";
            }
        }

        return implode(' ', $identityParts);
    }
}
