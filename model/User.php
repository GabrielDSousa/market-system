<?php
require "model/Model.php";

/**
 * Summary of User
 */
class User extends Model
{
    /**
     * Summary of table
     * @var string
     */
    protected string $table = "user";

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
        "password"
    ];

    /**
     * The rules to validate when adding a new User.
     * 
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "name" => "required|string",
        "email" => "required|email|unique:email",
        "password" => "required|min:6|max:255|same:confirmation",
        "confirmation" => "required|min:6|max:255"
    ];

    /**
     * User ID
     * @var ?int
     */
    private ?int $id;

    /**
     * User name
     * @var ?string
     */
    private ?string $name;

    /**
     * User email
     * @var ?string
     */
    private ?string $email;

    /**
     * User password
     * @var ?string
     */
    private ?string $password;

    /**
     * Tells if the user is an admin
     * @var ?bool
     */
    private ?bool $admin;



    public function __construct(
        ?string $name = null,
        ?string $email = null,
        ?string $password = null,
        ?bool $admin = false,
        ?int $id = null
    ) {
        parent::__construct();
        !empty($name) ?? $this->setName($name);
        !empty($email) ?? $this->setEmail($email);
        !empty($password) ?? $this->setPassword($password);
        !empty($admin) ?? $this->setAdmin($admin);
        !empty($id) ?? $this->id = $this->setId($id);
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return $this->rules;
    }

    /**
     * @param int $id User ID
     * @return self
     */
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     */
    public function getPassword(): string
    {
        if (isset($this->id) && empty($this->password)) {
            $this->password = $this->db->query("SELECT password FROM public.user WHERE id = :id", [":id" => $this->getId()])->findOrFail()["password"];
        }

        // Return the hashed password (for security reasons)
        return $this->password;
    }

    /**
     * Tells if the given password is the same as the user password
     * 
     * @return bool
     */
    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->password);
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
}
