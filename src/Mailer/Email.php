<?php 
namespace App\Mailer;

use App\Entity\User;
use App\Entity\UserToken;
use App\Utils\FakerTrait;
use App\Utils\ServiceTrait;

class Email {

    use FakerTrait;
    use ServiceTrait;

    public function __construct(
        private string $label,
        private string $template,
        private array $data,
        private bool $isMock = false
    ) {
    }
    
    /**
     * Get value of data
     *
     * @return array
     */
    public function getData(): array 
    {
        return $this->isMock ? $this->getMockedData() : $this->data;
    }
    
    /**
     * getMockedData
     *
     * @return array
     */
    private function getMockedData(): array 
    {
        $data = [];
        $user = new User;
        $token = new UserToken;
        $faker = $this->getFakerFactory();

        $user
            ->setEmail($faker->email())
            ->setFirstname($faker->firstName())
            ->setLastname($faker->lastName())
            ->setConfirm($faker->boolean())
            ->setUsername($faker->userName())
        ;
        $token
            ->setAction('ACTION')
            ->setCreatedAt($this->now())
            ->setExpiredAt($this->now()->modify('+1 days'))
            ->setToken($this->generateToken())
            ->setUser($user)
        ;

        if (in_array('user', $this->data)) {
            $data['user'] = $user;
        }
        if (in_array('user', $this->data) && array_key_exists('userWithPassword', $this->data)) {
            $data['user'] = $user->setPassword($this->data['userWithPassword']);
        }
        if (in_array('email', $this->data)) {
            $data['email'] = $faker->email();
        }
        if (in_array('token', $this->data)) {
            $data['token'] = $token;
        }

        return $data;
    }


    /**
     * Get the value of label
     */ 
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get the value of template
     */ 
    public function getTemplate()
    {
        return EMAIL_TEMPLATE_PATH . $this->template;
    }
}