# REST API

### Тип аутентификации: [JWT, HMAC256](https://jwt.io/)

Каждый запрос должен содержать заголовок `Authorization: Bearer <token>` 

## Карта

| URI                                                                      | Метод  | Описание                               |
|--------------------------------------------------------------------------|--------|----------------------------------------|
| **[Админ-панель](#admin)**                                               |        |                                        |
| [/admin/code/receive](#admin-code-receive)                               | POST   | Получить код админа через SMS          |
| [/admin/pages/layout/header/edit](#admin-layout-header-edit)             | PATCH  | Изменить наполнение подвала страниц    |
| [/admin/pages/layout/footer/edit](#admin-layout-footer-edit)             | PATCH  | Изменить наполнение шапки страниц      |
| [/admin/pages/home/edit](#admin-pages-home-edit)                         | PATCH  | Изменить наполнение главной страницы   |
| **[Админ-панель > Библитека](#admin-library)**                           |        |                                        |
| [/admin/library/advice/create](#admin-library-advice-create)             | PUT    | Создать статью совета в библиотеке     |
| [/admin/library/advice/{advice_id}/edit](#admin-library-advice-edit)     | PATCH  | Редактировать статью совета библиотеки |
| [/admin/library/advice/{advice_id}/delete](#admin-library-advice-delete) | DELETE | Удалить статью совета из библиотеке    |
| **[Личный кабинет](#lk)**                                                |        |                                        |
| [/lk/code/receive](#lk-code-receive)                                     | POST   | Отправить код через SMS                |
| [/lk/sign_in](#lk-sign_in)                                               | POST   | Получить токен пользователя            |
| **[API](#api)**                                                          |        |                                        |
| [/library/advice/list](#library-advice-list-get)                         | GET    | Получить статьи советов из библиотеки  |


---

<h1 id="admin">Админ-панель</h1>

<h2 id="admin-code-receive">Получить код админа через SMS</h2>
### URI: /admin/code/receive
### Method: POST
### Auth: none

### Payload
```json lines
{
  "phone": "+79995553232",
}
```

<h2 id="admin-code-receive">Войти в админ-панель</h2>
### URI: /admin/sign_in
### Method: POST
### Auth: none

### Payload
```json lines
{
  "phone": "+79995553232",
  "code": "some_code"
}
```

### Response
Status: 200

```json lines
{
  "token": "some.JWT.token"
}
```

<h2 id="admin-layout-header-edit">Изменить наполнение шапки страниц</h2>
### URI: /admin/layout/header/edit
### Method: PATCH
### Auth: JWT

### Payload

```json lines
// <empty>
```

### Response
Status: 200

```json lines
// <empty> 
```

<h2 id="admin-layout-footer-edit">Изменить наполнение подвала страниц</h2>
### URI: /admin/layout/footer/edit
### Method: PATCH
### Auth: JWT

### Payload

```json lines
{
  "phone": string,
  "email": string,
  "social": {
    "vk": string,
    "telegram": string
  },
  "banners": [string] // Base64 encoded images
}
```

### Response
Status: 200

```json lines
// <empty> 
```


<h2 id="admin-pages-home-edit">Изменить наполнение главной страницы</h2>

### URI: /admin/pages/home/edit
### Method: PATCH
### Auth: JWT

### Payload

```json lines
{
  "slider": [
    {
      "title": string,
      "description": string,
      "image": ""
    },
    ...
  ],
  "qa": [ // Questions-answers
    {
      "title": string,
      "description": string,
    },
    ...
  ]
}
```

### Response
Status: 200

```json lines
// <empty> 
```

<h1 id="admin-library">Админ-панель > Библиотека</h1>

<h2 id="admin-library-advice-create">Создать статью совета в библиотеке</h2>
### URI: /admin/library/advice/create
### Method: PUT
### Auth: JWT

### Payload

```json lines
{
  "thumbnail": string, // Base64 encoded image
  "title": string,
  "excerpt": string,
  "body": string,
  "tags": [string]
}
```

### Response
Status: 200

```json lines
// <empty> 
```

<h2 id="admin-library-advice-edit">Редактировать статью совета в библиотеке</h2>
### URI: /admin/library/advice/{advice_id}/edit
### Method: PATCH
### Auth: JWT

### Payload

```json lines
{
  "thumbnail": string, //Optional, Base64 encoded image
  "title": string, //Optional
  "excerpt": string, //Optional
  "body": string, //Optional
  "tags": [string] //Optional
}
```

### Response
Status: 200

```json lines
// <empty> 
```

<h2 id="admin-library-advice-delete">Удалить статью совета из библиотеки</h2>
### URI: /admin/library/advice/{advice_id}/delete
### Method: PATCH
### Auth: JWT

### Payload

```json lines
// <empty>
```

### Response
Status: 200

```json lines
// <empty> 
```

<h1 id="lk">Личный кабинет</h1>

<h2 id="lk-code-receive">Отправить код через SMS</h2>
### URI: /lk/code/receive
### Method: POST
### Auth: none

### Description
Отправить код на номер телефона

### Payload

```json lines
{
  "phone": "+79995553232"
}
```

### Response
Status: 200

```json lines
// <empty>
```

<h2 id="lk-sign_in">Получить токен пользователя</h2>
### URI: /lk/sign_in
### Method: POST
### Auth: none

### Description
`code` - код, полученный через [SMS уведомление](#lk-code-receive)

### Payload

```json lines
{
  "phone": "+79995553232",
  "code": "some_code"
}
```

### Response
Status: 200

```json lines
{
  "access_token": string
} 
```

<h1 id="api">API</h1>

<h2 id="library-advice-list-get">Удалить статью совета из библиотеки</h2>
### URI: /library/advice/list
### Method: GET
### Auth: none

### GET parameters

```json lines
{
  "page": 1 // By default
}
```

### Response
Status: 200

```json lines
[
  {
    "id": number,
    "title": string,
    "excerpt": string,
    "tags": [string],
    "createdAt": string, // timestamp,
    "thumbnail": string, // URL
    "body": string
  }
]
```