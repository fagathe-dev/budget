<?php
namespace App\Service;

use App\Mailer\MailerEnum;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;

final class AdminService 
{

    public function __construct(
        private UserRepository $userRepository,
        private CategoryRepository $categoryRepository
    ){}

    public function getData():array
    {
        return [
            'emails' => MailerEnum::getEmails(),
            'users' => $this->userRepository->findAll(),
            'categories' => $this->categoryRepository->findAll()
        ];
    }

}