<?php

declare(strict_types=1);

namespace Hiberus\Skeleton\Shared\Domain\ValueObject;

use Hiberus\Skeleton\Shared\Domain\Exception\InvalidValueException;

class Password
{
    private string $encoded;

    /**
     * @throws InvalidValueException
     */
    public function __construct(private string $password)
    {
        $this->validate($password);
        $this->encoded = $this->password($password);
    }

    public function value(): string
    {
        return $this->encoded;
    }

    public function __toString(): string
    {
        return $this->encoded;
    }

    public function isEquals(Password $passwordToVerify): bool
    {
        return password_verify(md5($passwordToVerify->password), $this->value());
    }

    private function password(string $password): string
    {
        if ($this->isEncoded($password)) {
            return $password;
        }

        return $this->encode($password);
    }

    private function isEncoded(string $password): bool
    {
        $passwordInfo = password_get_info($password);
        if ($passwordInfo['algo']) {
            return true;
        }

        return false;
    }

    private function encode(string $plainText): string
    {
        return password_hash(md5($plainText), PASSWORD_DEFAULT);
    }

    /**
     * @throws InvalidValueException
     */
    private function validate(string $plainText): void
    {
        if ('' === $plainText) {
            throw new InvalidValueException('Password is not valid');
        }
    }

    /** @return string */
    public function pass(): string
    {
        return $this->password;
    }
}
