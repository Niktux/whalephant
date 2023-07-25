<?php

declare(strict_types = 1);

namespace Whalephant\Model\ValueObjects;

final class PeclExtension
{
    private string
        $name;
    private ?string
        $version;
    private PeclInstallationMode
        $install;
    private ?string
        $configureOptions;
    private ?bool
        $enable;

    public function __construct(string $name, ?string $version, PeclInstallationMode $install, ?string $configureOptions = null, ?bool $enable = null)
    {
        $this->name = $name;
        $this->version = $version;
        $this->install = $install;
        $this->configureOptions = $configureOptions;
        $this->enable = $enable ?? $install->equals(PeclInstallationMode::pecl());
    }

    public function name(): string
    {
        return $this->name;
    }

    public function nameForInstall(): string
    {
        return $this->name . ($this->version ? "-$this->version": '');
    }

    public function version(): ?string
    {
        return $this->version;
    }

    public function install(): PeclInstallationMode
    {
        return $this->install;
    }

    public function configureOptions(): ?string
    {
        return $this->configureOptions;
    }

    public function enable(): bool
    {
        return $this->enable;
    }

    public function equals(self $extension): bool
    {
        return $this->nameForInstall() === $extension->nameForInstall();
    }
}
