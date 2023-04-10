# Market view

## Project Setup

On root

```sh
npm install
```

On api folder

```sh
composer install
```

### Compile and Hot-Reload for Front end Development

On project root

```sh
npm run dev
```

### Compile and serve for Back end Development

On folder api

```sh
php -S localhost:8080 -t Public
```

### Postman collection

I've uploaded to the folder postman a exported JSON file with the routes that the api provide, it's simple and
straightforward because of the time, but have all working routes are present.

### Lint with [ESLint](https://eslint.org/)

On project root

```sh
npm run lint
```

### Known issues

Don't have a lot of time to work on this project, but I've found some issues that I would like to fix if I had more
time.

- Don't have a route for update user.
- Don't have a route for delete user.
- Don't have a route to make a user admin. Need to do it manually on database.

### Good to know

- There some parts at the front end that are only seen by the admin, like the admin page on profile menu.
- The database already have a user with admin privileges, the email is: admin@gmail.com and the password is:
  administrator.
- I've taken seven days to do this project, but I've worked on it only on weekdays, so I've spent about 40 hours on it.

# Routes

## User

### GET

```
/users
```

#### Request Headers:

Authorization: Bearer Token
Request Headers:
Content-Type
application/json

### GET

```
/user
```

#### Request Headers:

Authorization: Bearer Token
Request Headers:
Content-Type
application/json

#### Query Params:

user?id=28

### POST

```
user/store
```

#### Request Headers:

Content-Type
application/json

#### Request Body:

```json
{
  "name": "Gabriel D Sousa",
  "email": "gabrielramos.email@gmail.com",
  "password": "LoremIpsum",
  "confirmation": "LoremIpsum"
}
```

### PUT

```
user/update
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "id": "1",
  "name": "Gabriel D. Sousa",
  "email": "gabrielramos.email@gmail.com"
}
```

### DEL

```
user/delete
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "id": 1
}
```

## Type

### GET

```
/types
```

#### Request Headers:

Content-Type
application/json

### GET

```
/type
```

#### Request Headers:

Authorization: Bearer Token

#### Query Params:

?id=1

### POST

```
type/store
```

#### Request Headers:

Authorization: Bearer Token

#### Request body

```json
{
  "name": "Alimentício",
  "tax": 27
}
```

### PUT

```
type/update
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body

```json
{
  "id": 1,
  "name": "Estética",
  "tax": 10
}
```

### DEL

```
type/delete
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body

```json
{
  "id": 2
}
```

## Sale

### GET

```
/sales
```

#### Request Headers:

Authorization: Bearer Token
Content-Type
application/json

### GET

```
/sale
```

#### Request Headers:

Authorization: Bearer Token

### Query Params

?id=1

### POST

```
sale/store
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "chart": "{ {product: 5, quantity:12, value:200, tax:42, total:242}, {product: 7, quantity:2, value:25.98, tax:2.72, total:28.70} }",
  "value": "225.98",
  "tax": "44.72",
  "total": "270.70",
  "user_id": 28
}
```

### PUT

```
type/update
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "id": 12,
  "name": "Cuidados pessoais",
  "tax": 22
}
```

### DEL

```
type/delete
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "id": 2
}
```

## Product

### GET

```
/products
```

#### Request Headers

Authorization: Bearer Token
Content-Type
application/json

### GET

```
/product
```

#### Request Body:

```json
{
  "id": 1
}
```

### POST

```
product/store
```

#### Request Headers:

Authorization: Bearer Token

```json
{
  "name": "Coke",
  "description": "Lorem ipsum dolor amet.",
  "value": 3,
  "type_id": 2
}
```

### PUT

```
product/update
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "name": "Coke zero",
  "description": "Lorem ipsum.",
  "value": 9,
  "type_id": 3,
  "id": 3
}
```

### DEL

```
product/delete
```

#### Request Headers:

Authorization: Bearer Token

#### Request Body:

```json
{
  "id": 3
}
```

## Auth

### POST

```
login
```

#### Request Body:

```json
{
  "email": "gabrielramos.email@gmail.com",
  "password": "LoremIpsum"
}
```

### POST

```
logout
```

#### Request Headers

Authorization: Bearer Token

```json
{
  "email": "gabrielramos.email@gmail.com"
}
```
