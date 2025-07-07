<?php

declare(strict_types=1);

namespace Recipes\RecipeManagement\Domain\Model;

/**
 * Benutzer
 */
class Benutzer extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{
    /**
     * UID
     *
     * @var int
     */
    protected $uid;

    /**
     * Benutzername
     *
     * @var string
     */
    protected $benutzerName = '';

    /**
     * Passwort
     *
     * @var string
     */
    protected $benutzerPassword = '';

    /**
     * E-Mail-Adresse
     *
     * @var string
     */
    protected $benutzerEmail = '';

    /**
     * Getter für UID
     *
     * @return int
     */
    public function getUid(): int
    {
        return $this->uid;
    }

    /**
     * Setter für UID (optional)
     *
     * @param int $uid
     * @return void
     */
    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    /**
     * Getter für Benutzername
     *
     * @return string
     */
    public function getBenutzerName(): string
    {
        return $this->benutzerName;
    }

    /**
     * Setter für Benutzername
     *
     * @param string $benutzerName
     * @return void
     */
    public function setBenutzerName(string $benutzerName): void
    {
        $this->benutzerName = $benutzerName;
    }

    /**
     * Getter für Passwort
     *
     * @return string
     */
    public function getBenutzerPassword(): string
    {
        return $this->benutzerPassword;
    }

    /**
     * Setter für Passwort
     *
     * @param string $benutzerPassword
     * @return void
     */
    public function setBenutzerPassword(string $benutzerPassword): void
    {
        $this->benutzerPassword = $benutzerPassword;
    }

    /**
     * Getter für E-Mail-Adresse
     *
     * @return string
     */
    public function getBenutzerEmail(): string
    {
        return $this->benutzerEmail;
    }

    /**
     * Setter für E-Mail-Adresse
     *
     * @param string $benutzerEmail
     * @return void
     */
    public function setBenutzerEmail(string $benutzerEmail): void
    {
        $this->benutzerEmail = $benutzerEmail;
    }
}
