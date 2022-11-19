<?php


namespace App\Traits;



use App\Notifications\VerifyEmail;

trait MustVerifyEmail
{
    /**
     * Determine if the user has verified their email address.
     *
     * @return bool
     */
    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    /**
     * Mark the given user's email as verified.
     *
     * @return bool
     */
    public function markEmailAsVerified()
    {
        return $this->forceFill([
            'email_verify_code' => NULL,
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification(bool $newData = false)
    {
        if($newData)
        {
            $this->forceFill([
                'email_verify_code' => random_int(111111, 999999),
                'email_verify_code_sent_at' => now(),
            ])->save();
        }
        $this->notify(new VerifyEmail($this->email_verify_code));
    }

    /**
     * Get the email address that should be used for verification.
     *
     * @return string
     */
    public function getEmailForVerification()
    {
        return $this->email;
    }

    /**
     * Get the code that should be used for verification.
     *
     * @return string
     */
    public function getEmailVerifyCode()
    {
        return $this->email_verify_code;
    }

}
