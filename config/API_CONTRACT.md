# API Contract — Pikow Back

Base URL : `http://symfony.mmi-troyes.fr:8319/api`

## Authentification

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
  "nbPlayers": 4,
  "players": [
    { "name": "Alice", "age": 10 },
    { "name": "Bob", "age": 12 },
    { "name": "Léa", "age": 8 },
    { "name": "Max", "age": 14 }
  ]
}
```

**Réponse 201**
```json
{
  "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "theme": "famille",
  "nbPlayers": 4,
  "status": "en_cours",
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
    "status": "terminée",
    "date": "2026-06-16T20:00:00+00:00",
    "pitchCount": 4
  }
]
```

---

### PATCH /games/{id}
Terminer une partie.

**Headers**
Authorization: Bearer {token}

**Body**
```json
{
  "status": "terminée"
}
```

**Réponse 200**
```json
{
  "id": "018f4e2a-7b3c-7000-8e2a-1234567890ab",
  "status": "terminée"
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

---

## Codes d'erreur communs

| Code | Signification |
|------|--------------|
| 400 | Données invalides |
| 401 | Token manquant ou expiré |
| 403 | Accès interdit |
| 404 | Ressource introuvable |
| 500 | Erreur serveur |
