<?php

namespace Model;

use App\Requests\Request;
use Exception;

/**
 * This class is responsible for the token model
 */
class Token extends Model
{
    /**
     * The array of model visible attributes.
     *
     * @var array
     */
    protected array $visible = [
        "id",
        "token",
        "user_id"
    ];

    /**
     * The array of model fillable attributes.
     *
     * @var array
     */
    protected array $fillable = [
        "token",
        "user_id"
    ];

    /**
     * The rules to validate when adding a new User.
     *
     * @var array
     */
    protected array $rules = [
        "id" => "integer",
        "token" => "required|string",
        "user_id" => "required|integer"
    ];

    /**
     * @var string
     */
    protected string $token;

    /**
     * @var int
     */
    protected int $user_id;

    /**
     * The jwt configuration array
     * @var array
     */
    protected array $configJwt;

    /**
     * @var User
     */
    protected User $user;

    /**
     * @throws Exception
     */
    public function __construct(
        string $token = "",
        int $user_id = 0,
        int $id = 0
    ) {
        parent::__construct();
        $this->setToken($token);
        $this->setUserId($user_id);
        $this->setId($id);
        $this->configJwt = config("jwt");
    }

    /**
     * @param string $token Type name
     * @return self
     */
    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     * @return self
     * @throws Exception
     */
    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * Check if the user has a valid token
     *
     * @return self|bool
     * @throws Exception
     */
    public function hasToken(): self|bool
    {
        try {
            $parameters = $this->getByColumn("user_id", $this->getUserId())->findOrFail();
        } catch (Exception $e) {
            if ($e->getCode() === Request::NOT_FOUND) {
                return false;
            }

            throw $e;
        }

        if ($parameters) {
            $this->setAttributes($parameters);
        }

        $this->tokenIsValid();
        return $this;
    }

    /**
     * Check if the token is valid
     *
     * @return self
     * @throws Exception
     */
    public function tokenIsValid(): self
    {
        if (!empty($this->getToken())) {
            return $this->isJwtValid($this->getToken());
        }

        throw new Exception("Token is empty", Request::UNAUTHORIZED);
    }

    /**
     * Create a new token for the user
     *
     * @return self
     * @throws Exception
     */
    public function createToken(): self
    {
        // Check if the user has a valid token
        $hasToken = $this->hasToken();

        if ($hasToken) {
            return $hasToken;
        }

        // Generate a new token
        $headers = array('alg' => 'HS256', 'typ' => 'JWT');
        $payload = array('username' => $this->user->getEmail(), 'exp' => (time() + $this->configJwt["expiration"]));

        $token = $this->createJwtToken($headers, $payload);

        $this->setToken($token);
        $this->setUserId($this->user->getId());

        return $this;
    }

    /**
     * This function generates a JWT.
     *
     * @param array $headers
     * @param array $payload
     * @return string
     */
    private function createJwtToken(array $headers, array $payload): string
    {
        // Encode the headers and the payload
        $headers_encoded = base64url_encode(json_encode($headers));
        $payload_encoded = base64url_encode(json_encode($payload));

        // Create the signature
        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->configJwt["secret"], true);
        $signature_encoded = base64url_encode($signature);

        // Create the JWT
        return "$headers_encoded.$payload_encoded.$signature_encoded";
    }

    /**
     * This function checks if a JWT is valid.
     *
     * @param string $jwt
     * @return Token
     * @throws Exception
     */
    public function isJwtValid(string $jwt): Token
    {
        // split the jwt
        $tokenParts = explode('.', $jwt);
        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signature_provided = $tokenParts[2];

        // check the expiration time - note this will cause an error if there is no 'exp' claim in the jwt
        $expiration = json_decode($payload)->exp;
        $is_token_expired = ($expiration - time()) < 0;

        if ($is_token_expired) {
            throw new Exception("Token expired", Request::UNAUTHORIZED);
        }

        // build a signature based on the header and payload using the secret
        $base64_url_header = base64url_encode($header);
        $base64_url_payload = base64url_encode($payload);
        $signature = hash_hmac(
            'SHA256',
            $base64_url_header . "." . $base64_url_payload,
            $this->configJwt["secret"],
            true
        );
        $base64_url_signature = base64url_encode($signature);

        // verify it matches the signature provided in the jwt
        $is_signature_valid = ($base64_url_signature === $signature_provided);

        if (!$is_signature_valid) {
            throw new Exception("Invalid signature", Request::FORBIDDEN);
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getUser(): User
    {
        if ($this->user_id === 0) {
            throw new Exception("User id is empty", Request::NOT_FOUND);
        }

        if (empty($this->user)) {
            $this->setUser((new User())->get($this->getUserId()));
        }

        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user_id = $user->getId();
        $this->user = $user;
    }

    /**
     * @param string $token
     * @return self
     * @throws Exception
     */
    public function getByToken(string $token): self
    {
        try {
            $params = $this->getByColumn("token", $token)->findOrFail();
        } catch (Exception $e) {
            if ($e->getCode() === Request::NOT_FOUND) {
                throw new Exception("Token not found", Request::UNAUTHORIZED);
            }

            throw $e;
        }
        $this->setAttributes($params);
        return $this;
    }
}
