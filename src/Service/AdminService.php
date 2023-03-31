<?php
namespace App\Service;

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
            'users' => $this->userRepository->findAll(),
            'categories' => $this->categoryRepository->findAll()
        ];
    }

}