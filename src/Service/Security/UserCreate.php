<?php

namespace App\Service\Security;

use App\Config\TokenTypeEnum;
use App\Config\UserTypeEnum;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Brevo\SendEmailConfirmRegistration;
use App\Service\Security\Token\TokenConfirmRegistrationCreate;
use SendinBlue\Client\Model\CreateSmtpEmail;
use SendinBlue\Client\ApiException;
use stdClass;
use Exception;

class UserCreate
{
    public function __construct(
        private readonly UserPasswordHash $passwordHash,
        private readonly UserRepository $userRepository,
        private readonly SendEmailConfirmRegistration $mailer,
        private readonly TokenConfirmRegistrationCreate $tokenCreator,
    ) {}

    /**
     * @param User $user
     * @return User
     * @throws Exception
     */
    public function execute(User $user, string $type): User
    {
//        dd($type);
        $user = $this->additionalTreatmentUserType($user, $type);

        // Hash password and add role
        $user->setPassword($this->passwordHash->hash($user));

        // Create a tokensRegistry with token confirm registration
        $token = $this->tokenCreator->create();
        $user->setTokens($token);

        // Persist and catch exception
        try {
            $this->userRepository->save($user, true);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }

        // Send confirmation email
        $this->sendEmailConfirmRegistration($user);

        return $user;
    }

    /**
     * @param User $user
     * @return CreateSmtpEmail
     * @throws ApiException
     */
    private function sendEmailConfirmRegistration(User $user): CreateSmtpEmail
    {
        // Prépare params for email
        $params = new stdClass();
        $params->firstname = $user->getFirstname();
        $params->token = $user->getTokens()->getConfirmRegister();

        // Send email
        return $this->mailer->sendEmail($user->getEmail(), $params);
    }

    /**
     * @param User $user
     * @param string $type
     * @return User
     */
    private function additionalTreatmentUserType(User $user, string $type): User
    {
        switch ($type) {
            case UserTypeEnum::PARTICULIER->value :
                $user->setProfessional(false)
                    ->setRoles(['ROLE_USER']);
                !isset($user->getAssociations()[0]) ?: $user->removeAssociation($user->getAssociations()[0]);
                break;
            case UserTypeEnum::PROFESSIONNEL->value :
                $user->setProfessional(true)
                    ->setRoles(['ROLE_USER']);
                !isset($user->getAssociations()[0]) ?: $user->removeAssociation($user->getAssociations()[0]);
                break;
            case UserTypeEnum::ASSOCIATION->value:
                $user->setProfessional(false)
                    ->setRoles(['ROLE_USER', 'ROLE_ASSOCIATION']);
                // @Todo a gérér : le mail est obligatoire pour le persist
                $user->getAssociations()[0]->setEmail($user->getEmail());
                break;
        }

        return $user;
    }
}