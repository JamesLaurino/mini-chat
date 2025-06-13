<p align="center">
  <img src="https://raw.githubusercontent.com/JamesLaurino/mini-chat/refs/heads/master/logo.png" 
alt="Mini-Chat Logo" width="50px" height="70px"/>
</p>

<h1 align="center">Mini-Chat</h1>

<p align="center">
  Une application de chat inspirée de ChatGPT, propulsée par Laravel, Vue.js et Inertia.js.
</p>

---

## ✨ Fonctionnalités

- 🔥 Interface moderne inspirée de ChatGPT
- 🔐 Limite de **50 prompts par jour par utilisateur**
- ⚙️ Préférences utilisateur personnalisables :
    - À propos
    - Instructions à l'IA
    - Comportement de l'IA
- 🧠 Intégration avec **OpenRouter** :
    - Choix complet des modèles disponibles
- 📦 Backend Laravel + MySQL
- 💬 Frontend Vue.js + Inertia.js

---

## 🚀 Lancement en local

### 1. Cloner le projet

a)  git clone https://github.com/ton-utilisateur/mini-chat.git

b) docker run -d -p 3306:3306 --name mysql-test \
  -e MYSQL_ROOT_PASSWORD=<MON_PASSWORD> \
  -e MYSQL_DATABASE=<MA_DATABASE> \
  mysql:latest

c) docker run -d -p 3307:3306 --name mysql-prod \
  -e MYSQL_ROOT_PASSWORD=<MON_PASSWORD> \
  -e MYSQL_DATABASE=<MA_DATABASE>\
  mysql:latest

d) cp .env.example .env : 3306 mysql port et OPENROUTER_API_KEY=myKey
e) cp .env .env.dusk.local : 3307 mysql port et OPENROUTER_API_KEY=myKey

f) php -S localhost:8000 -t public
g) npm run dev

h) php artisan migrate --seed
