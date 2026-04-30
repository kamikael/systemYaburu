## requete post man

1️⃣ INSCRIPTION - POST
POST http://localhost:8000/api/register
Content-Type: application/json

{
  "email": "user@example.com",
  "name": "John Doe",
  "password": "password123",
  "password_confirmation": "password123"
}
Réponse attendue (201):
json{
  "message": "Utilisateur créé avec succès",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz..."
}

2️⃣ CONNEXION - POST
POST http://localhost:8000/api/login
Content-Type: application/json

{
  "email": "user@example.com",
  "password": "password123"
}
Réponse attendue (200):
json{
  "message": "Connexion réussie",
  "user": {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  },
  "token": "1|abcdefghijklmnopqrstuvwxyz..."
}
⚠️ IMPORTANT : Copie ce token pour les requêtes suivantes !

3️⃣ MON PROFIL - GET
GET http://localhost:8000/api/me
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json{
  "id": 1,
  "email": "user@example.com",
  "name": "John Doe",
  "created_at": "2024-01-10T10:00:00.000000Z",
  "updated_at": "2024-01-10T10:00:00.000000Z"
}

4️⃣ RÉCUPÉRER UN UTILISATEUR - GET
GET http://localhost:8000/api/users/1
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json{
  "id": 1,
  "email": "user@example.com",
  "name": "John Doe",
  "created_at": "2024-01-10T10:00:00.000000Z",
  "updated_at": "2024-01-10T10:00:00.000000Z"
}

5️⃣ METTRE À JOUR LE PROFIL - PUT
PUT http://localhost:8000/api/users/1
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...

{
  "name": "Jane Doe",
  "email": "newemail@example.com"
}
Réponse attendue (200):
json{
  "message": "Profil mis à jour",
  "user": {
    "id": 1,
    "email": "newemail@example.com",
    "name": "Jane Doe",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:05:00.000000Z"
  }
}

6️⃣ CHANGER LE MOT DE PASSE - POST
POST http://localhost:8000/api/users/1/change-password
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...

{
  "current_password": "password123",
  "password": "newpassword456",
  "password_confirmation": "newpassword456"
}
Réponse attendue (200):
json{
  "message": "Mot de passe changé avec succès"
}

7️⃣ LISTER TOUS LES UTILISATEURS - GET (Admin)
GET http://localhost:8000/api/users
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json[
  {
    "id": 1,
    "email": "user@example.com",
    "name": "John Doe",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:05:00.000000Z"
  },
  {
    "id": 2,
    "email": "user2@example.com",
    "name": "Jane Smith",
    "created_at": "2024-01-10T10:10:00.000000Z",
    "updated_at": "2024-01-10T10:10:00.000000Z"
  }
]

8️⃣ SUPPRIMER UN UTILISATEUR - DELETE
DELETE http://localhost:8000/api/users/1
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json{
  "message": "Utilisateur supprimé"
}

9️⃣ CRÉER UNE BOUTIQUE - POST
POST http://localhost:8000/api/shops
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...

{
  "shop_name": "Ma Super Boutique",
  "description": "Une boutique de produits électroniques",
  "logo_url": "https://example.com/logo.png"
}
Réponse attendue (201):
json{
  "message": "Boutique créée",
  "shop": {
    "id": 1,
    "user_id": 1,
    "shop_name": "Ma Super Boutique",
    "shop_slug": "ma-super-boutique-65a1b2c3d",
    "description": "Une boutique de produits électroniques",
    "logo_url": "https://example.com/logo.png",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  }
}

🔟 RÉCUPÉRER MES BOUTIQUES - GET
GET http://localhost:8000/api/users/1/shops
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json[
  {
    "id": 1,
    "user_id": 1,
    "shop_name": "Ma Super Boutique",
    "shop_slug": "ma-super-boutique-65a1b2c3d",
    "description": "Une boutique de produits électroniques",
    "logo_url": "https://example.com/logo.png",
    "created_at": "2024-01-10T10:00:00.000000Z",
    "updated_at": "2024-01-10T10:00:00.000000Z"
  }
]

1️⃣1️⃣ DÉTAILS D'UNE BOUTIQUE - GET
GET http://localhost:8000/api/shops/1
Content-Type: application/json
Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz...
Réponse attendue (200):
json{
  "id": 1,
  "user_id": 1,
  "shop_name": "Ma Super Boutique",
  "shop_slug": "ma-super-boutique-65a1b2c3d",
  "description": "Une boutique de produits électroniques",
  "logo_url": "https://example.com/logo.png",
  "created_at": "2024-01-10T10:00:00.000000Z",
  "updated_at": "2024-01-10T10:00:00.000000Z"
}