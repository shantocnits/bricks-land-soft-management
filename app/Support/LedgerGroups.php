<?php

namespace App\Support;

use App\Models\Ledger;
use App\Models\Setting;

class LedgerGroups
{
    public const DEFAULT_GROUPS = ['কাস্টমার', 'সরবরাহকারী', 'লেবার', 'মাটি', 'স্টাফ', 'খরচ', 'আয়', 'অন্যান্য'];

    public const SETTING_KEY = 'ledger_groups';

    public static function fromSetting(): array
    {
        $json = Setting::get(self::SETTING_KEY);

        return $json ? (json_decode($json, true) ?: []) : [];
    }

    public static function fromDb(): array
    {
        return Ledger::active()
            ->whereNotNull('group')
            ->pluck('group')
            ->map(fn ($g) => trim($g))
            ->filter(fn ($g) => $g !== '')
            ->unique()
            ->values()
            ->toArray();
    }

    public static function all(bool $includeDefaults = true, bool $includeInactive = false): array
    {
        $inactives = self::inactiveGroups();

        // Clean up any inactives that no longer have any active ledgers AND no payments in database
        $validInactives = [];
        foreach ($inactives as $inGroup) {
            $hasActiveLedger = Ledger::active()->where('group', $inGroup)->exists();
            $groupLedgers = Ledger::where('group', $inGroup)->pluck('name')->toArray();
            $groupLedgers[] = $inGroup;
            $hasPayment = \App\Models\Payment::whereIn('ledger', $groupLedgers)->exists();

            if ($hasActiveLedger || $hasPayment) {
                $validInactives[] = $inGroup;
            } else {
                // Auto-cleanup permanent delete if no active ledger and no payments remain
                self::remove($inGroup);
                self::markActive($inGroup);
                Ledger::where('group', $inGroup)->delete();
            }
        }

        $merged = $includeDefaults
            ? array_merge(self::fromSetting(), self::fromDb(), self::DEFAULT_GROUPS)
            : array_merge(self::fromSetting(), self::fromDb());

        if ($includeInactive && !empty($validInactives)) {
            $merged = array_merge($merged, $validInactives);
        }

        $inactiveLowers = array_map(fn($g) => mb_strtolower(trim($g), 'UTF-8'), $validInactives);

        $seen = [];
        $unique = [];
        foreach ($merged as $g) {
            $trimmed = trim($g);
            if ($trimmed === '') {
                continue;
            }
            $lower = mb_strtolower($trimmed, 'UTF-8');
            if (!$includeInactive && (in_array($lower, $inactiveLowers) || self::isInactive($trimmed))) {
                continue;
            }
            if (!isset($seen[$lower])) {
                $seen[$lower] = true;
                $unique[] = $trimmed;
            }
        }

        return array_values($unique);
    }

    public static function save(array $groups): void
    {
        $groups = array_values(array_filter(array_map('trim', $groups), fn ($g) => $g !== ''));

        Setting::set(self::SETTING_KEY, json_encode($groups));
    }

    /**
     * Persist the group in the settings list. Returns the canonical group name.
     */
    public static function add(string $name): string
    {
        $name = trim($name);
        if ($name === '') {
            return '';
        }

        $groups = self::fromSetting();
        foreach ($groups as $group) {
            if (mb_strtolower(trim($group), 'UTF-8') === mb_strtolower($name, 'UTF-8')) {
                return $group;
            }
        }

        array_unshift($groups, $name);
        self::save($groups);

        return $name;
    }

    public static function inactiveGroups(): array
    {
        $json = Setting::get('inactive_groups');
        return $json ? (json_decode($json, true) ?: []) : [];
    }

    public static function isInactive(string $groupName): bool
    {
        $inactives = self::inactiveGroups();
        foreach ($inactives as $g) {
            if (mb_strtolower(trim($g), 'UTF-8') === mb_strtolower(trim($groupName), 'UTF-8')) {
                return true;
            }
        }
        return false;
    }

    public static function markInactive(string $groupName): void
    {
        $inactives = self::inactiveGroups();
        if (!self::isInactive($groupName)) {
            $inactives[] = trim($groupName);
            Setting::set('inactive_groups', json_encode(array_values(array_unique($inactives))));
        }
    }

    public static function markActive(string $groupName): void
    {
        $inactives = self::inactiveGroups();
        $inactives = array_values(array_filter($inactives, fn($g) => mb_strtolower(trim($g), 'UTF-8') !== mb_strtolower(trim($groupName), 'UTF-8')));
        Setting::set('inactive_groups', json_encode($inactives));
    }

    public static function remove(string $name): void
    {
        $groups = self::fromSetting();
        $groups = array_values(array_filter($groups, fn ($g) => mb_strtolower(trim($g), 'UTF-8') !== mb_strtolower(trim($name), 'UTF-8')));
        self::save($groups);
    }
}
