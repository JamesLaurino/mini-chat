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

<h2>1. Cloner le Projet</h2>
  <p>Commencez par cloner le dépôt sur votre machine locale :</p>
  <pre><code>git clone https://github.com/ton-utilisateur/mini-chat.git</code></pre>

<h2>2. Configuration de la Base de Données (Docker)</h2>
  <p>Nous utiliserons Docker pour configurer vos bases de données MySQL pour les environnements de test et de production. <strong>N'oubliez pas de remplacer <code>&lt;MON_PASSWORD&gt;</code> et <code>&lt;MA_DATABASE&gt;</code> par le mot de passe fort et le nom de base de données souhaités.</strong></p>

<h3>a) MySQL pour les Tests</h3>
  <pre><code>docker run -d -p 3306:3306 --name mysql-test \
  -e MYSQL_ROOT_PASSWORD=&lt;MON_PASSWORD&gt; \
  -e MYSQL_DATABASE=&lt;MA_DATABASE&gt; \
  mysql:latest</code></pre>

<h3>b) MySQL pour la Production</h3>
  <pre><code>docker run -d -p 3307:3306 --name mysql-prod \
  -e MYSQL_ROOT_PASSWORD=&lt;MON_PASSWORD&gt; \
  -e MYSQL_DATABASE=&lt;MA_DATABASE&gt; \
  mysql:latest</code></pre>

<h2>3. Configuration de l'Environnement</h2>

<h3>a) Fichier d'Environnement Principal</h3>
  <p>Créez votre fichier d'environnement principal et configurez-le :</p>
  <pre><code>cp .env.example .env</code></pre>
  <p><strong>Modifiez le fichier <code>.env</code> :</strong></p>
  <ul>
    <li>Configurez votre connexion MySQL (port <code>3306</code> pour la base de données de test).</li>
    <li>Définissez votre <code>OPENROUTER_API_KEY</code> sur <code>myKey</code> (ou votre clé réelle).</li>
  </ul>

<h3>b) Fichier d'Environnement Dusk</h3>
  <p>Créez un fichier d'environnement séparé pour les tests Dusk :</p>
  <pre><code>cp .env.example .env.dusk.local</code></pre>
  <p><strong>Modifiez le fichier <code>.env.dusk.local</code> :</strong></p>
  <ul>
    <li>Configurez votre connexion MySQL (port <code>3307</code> pour la base de données de test).</li>
    <li>Définissez votre <code>OPENROUTER_API_KEY</code> sur <code>myKey</code> (ou votre clé réelle).</li>
  </ul>

<h2>4. Lancer l'Application</h2>

<h3>a) Serveur PHP Intégré</h3>
  <p>Démarrez le serveur de développement PHP :</p>
  <pre><code>php -S localhost:8000 -t public</code></pre>

<h3>b) Actifs Frontend</h3>
  <p>Compilez et servez vos actifs frontend :</p>
  <pre><code>npm run dev</code></pre>

<h2>5. Migrations et Remplissage de la Base de Données</h2>
  <p>Exécutez vos migrations de base de données et remplissez la base de données avec les données initiales :</p>
  <pre><code>php artisan migrate:fresh --seed</code></pre>

<h2>6. Lancer les tests unitaires</h2>
  <p>Exécutez les tests unitaires</p>
  <pre><code>./vendor/bin/pest tests/Unit/</code></pre>

<h2>7. Lancer les Tests dusk</h2>
  <p>Exécutez les tests unitaires</p>
  <pre><code>php artisan dusk</code></pre>


  <p>Et voilà ! Votre projet Mini-Chat devrait maintenant être configuré et prêt à l'emploi.</p>
