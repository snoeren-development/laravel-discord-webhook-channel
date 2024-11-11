<?php

namespace SnoerenDevelopment\DiscordWebhook;

use Illuminate\Contracts\Support\Arrayable;

/** @implements Arrayable<string, mixed> */
class DiscordMessageAllowedMentions implements Arrayable
{

    /** List of users that can be pinged or wheter or not to allow users pings. */
    protected array|bool|null $allowUsers = null;

    /** List of roles that can be pinged or whether or not to allow roles pings. */
    protected array|bool|null $allowRoles = null;

    /** Indicates that "everyone" mentions are allowed. */
    protected bool|null $allowEveryone = null;

    /** Create a new Discord allowed mentions instance. */
    public static function create(): self
    {
        return new self;
    }

    /** Set whether or not to allow "everyone" pings. */
    public function everyone(bool $allowed): self
    {
        $this->allowEveryone = $allowed;
        return $this;
    }

    /** Set whether or not to allow user pings. */
    public function allUsers(bool $allowed): self
    {
        $this->allowUsers = $allowed;
        return $this;
    }

    /** 
     * Set what users can be pinged.
     * 
     * @param array<string> $userIds
     */
    public function allowUsers(array $userIds): self
    {
        $this->allowUsers = $userIds;
        return $this;
    }

    /** Set whether or not to allow role pings. */
    public function allRoles(bool $allowed): self
    {
        $this->allowRoles = $allowed;
        return $this;
    }

    /** 
     * Set what roles can be pinged.
     * 
     * @param array<string> $roleIds
     */
    public function allowRoles(array $roleIds): self
    {
        $this->allowRoles = $roleIds;
        return $this;
    }

    /** Disable all mentions. */
    public function disableAll(): self
    {
        $this->allowUsers = false;
        $this->allowRoles = false;
        $this->allowEveryone = false;
        return $this;
    }

    /**
     * Get the instance as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $parseArray = null;

        if ($this->allowUsers === false || $this->allowRoles === false || $this->allowEveryone === false) {
            $parseArray = []; // Empty array denotes that no mentions are allowed
        }

        if ($this->allowUsers === true) {
            $parseArray ??= [];
            $parseArray[] = "users";
        }

        if ($this->allowRoles === true) {
            $parseArray ??= [];
            $parseArray[] = "roles";
        }

        if ($this->allowEveryone === true) {
            $parseArray ??= [];
            $parseArray[] = "everyone";
        }

        $output = [
            "parse" => $parseArray,
        ];

        if (is_array($this->allowUsers)) {
            $output["users"] = array_values($this->allowUsers);
        }

        if (is_array($this->allowRoles)) {
            $output["roles"] = array_values($this->allowRoles);
        }

        return $output;
    }
}