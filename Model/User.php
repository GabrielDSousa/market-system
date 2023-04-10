<?php

namespace Model;

use Exception;

class User extends Model
{
    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [
        "id",
        "name",
        "email",
        "admin"
    ];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [
        "name",
        "email",
        "password",
        "admin"
    ];

    /**
     * The rules to validate when adding a new User.
     *
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "name" => "required|string",
        "email" => "required|email|unique:user,email",
        "password" => "required|min:6|max:255|same:confirmation",
        "confirmation" => "required|min:6|max:255"
    ];

    /**
     * User name
     * @var string
     */
    protected string $name;

    /**
     * User email
     * @var string
     */
    protected string $email;

    /**
     * User password
     * @var string
     */
    protected string $password;

    /**
     * Tells if the user is an admin
     * @var bool
     */
    protected bool $admin = false;


    public function __construct(
        string $name = "",
        string $email = "",
        bool $admin = false,
        int $id = 0
    ) {
        parent::__construct();
        $this->setName($name);
        $this->setEmail($email);
        $this->setAdmin($admin);
        $this->setId($id);
        $this->password = "";
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name User name
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email User email
     * @return self
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    /**
     * Hash and set password
     *
     * @param string $password User password
     * @return self
     */
    public function setPassword(string $password): self
    {
        // Hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);
        $this->password = $password;
        return $this;
    }

    /**
     * @return string
     * @throws Exception
     */
    public function getPassword(): string
    {
        if ($this->id !== 0 && empty($this->password)) {
            $this->password = $this->db->query(
                "SELECT password FROM public.users WHERE id = :id",
                [":id" => $this->getId()]
            )->findOrFail()["password"];
        }

        // Return the hashed password (for security reasons)
        return $this->password;
    }

    /**
     * Tells if the given password is the same as the user password
     *
     * @param string $password
     * @return bool
     * @throws Exception
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->getPassword());
    }

    /**
     * Tells if the user is an admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->admin;
    }

    /**
     * @param bool $admin Tells if the user is an admin
     * @return self
     */
    public function setAdmin(bool $admin): self
    {
        $this->admin = $admin;
        return $this;
    }

    /**
     * Get user by email
     *
     * @param string $email User email
     * @return self
     * @throws Exception
     */
    public function getByEmail(string $email): self
    {
        $params = $this->getByColumn("email", $email)->findOrFail();
        $this->setAttributes($params);

        return $this;
    }
}
