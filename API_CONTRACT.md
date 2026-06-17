# API Contract — Pikow Back

Base URL : `http://symfony.mmi-troyes.fr:8319/api`

## Authentification

### POST /register
Créer un compte utilisateur.

**Body**
```json
{
  "email": "user@example.com",
  "password": "motdepasse"
}
```

**Réponse 201**
```json
{
  "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "email": "user@example.com"
}
```

**Réponse 422**
```json
{
  "violations": [
    { "propertyPath": "email", "message": "L'email n'est pas valide." }
  ]
}
```

### POST /login
Connexion et récupération du token JWT.

**Body**
```json
{
  "email": "user@example.com",
  "password": "motdepasse"
}
```

**Réponse 200**
```json
{
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9..."
}
```

**Réponse 401**
```json
{
  "code": 401,
  "message": "Invalid credentials."
}
```

---

## Éléments

### GET /elements/random
Tire 2 éléments aléatoires selon le thème et l'âge du joueur.

**Query params**
- `theme` (string, requis) : famille | business | spicy
- `age` (int, requis) : âge du joueur courant

**Réponse 200**
```json
[
  {
    "id": 12,
    "type": "mot",
    "value": "Trampoline",
    "theme": "famille"
  },
  {
    "id": 34,
    "type": "mot",
    "value": "Aspirateur",
    "theme": "famille"
  }
]
```

---

## Parties

### POST /games
Créer une nouvelle partie.

**Headers**  
`Authorization: Bearer {token}`

**Body**
```json
{
  "theme": "famille",
  "nbPlayers": 4
}
```

**Réponse 201**
```json
{
  "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "theme": "famille",
  "nbPlayers": 4,
  "status": "in_progress",
  "date": "2026-06-16T20:00:00+00:00"
}
```

---

### GET /games
Historique des parties de l'utilisateur connecté.

**Headers**  
`Authorization: Bearer {token}`

**Réponse 200**
```json
[
  {
    "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
    "theme": "famille",
    "nbPlayers": 4,
    "status": "finished",
    "date": "2026-06-16T20:00:00+00:00"
  }
]
```

---

### PATCH /games/{id}
Terminer une partie.

**Headers**  
`Authorization: Bearer {token}`  
`Content-Type: application/merge-patch+json`

**Body**
```json
{
  "status": "finished"
}
```

**Réponse 200**
```json
{
  "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "status": "finished"
}
```

---

## Pitchs

### POST /pitches
Enregistrer un pitch.

**Headers**  
`Authorization: Bearer {token}`

**Body**
```json
{
  "gameId": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "playerName": "Alice",
  "playerAge": 10,
  "turnNumber": 1,
  "word1Id": 12,
  "word2Id": 34,
  "duration": 60,
  "score": 3
}
```

**Réponse 201**
```json
{
  "id": "018f4e2b-7b3c-7000-8e2a-1234567890ab",
  "playerName": "Alice",
  "turnNumber": 1,
  "duration": 60,
  "score": 3
}
```

---

### GET /games/{id}/pitches
Récupérer tous les pitchs d'une partie.

**Headers**  
`Authorization: Bearer {token}`

**Réponse 200**
```json
[
  {
    "id": "018f4e2b-7b3c-7000-8e2a-1234567890ab",
    "playerName": "Alice",
    "playerAge": 10,
    "turnNumber": 1,
    "duration": 60,
    "score": 3,
    "word1": { "id": 12, "value": "Trampoline" },
    "word2": { "id": 34, "value": "Aspirateur" }
  }
]
```

## Contact

### POST /contact
Envoyer un formulaire de contact ou une demande revendeur.   
**Public, pas de token requis.**

**Body**
```json
{
  "type": "contact",
  "email": "test@example.com",
  "message": "Bonjour, je souhaite des informations.",
  "company": null
}
```

**type** : `contact` | `revendeur`
**company** : obligatoire si `type = revendeur`, nullable sinon

**Réponse 201**
```json
{
  "id": 1
}
```

---

## Codes d'erreur communs

| Code | Signification |
|------|--------------|
| 400 | Données invalides |
| 401 | Token manquant ou expiré |
| 403 | Accès interdit |
| 404 | Ressource introuvable |
| 500 | Erreur serveur |
