<template>
    <div class="quota-exceeded-container">
        <div class="content">
            <div class="icon">
        <span>
          Oops ...
        </span>
            </div>
            <h1>Quota Quotidien Dépassé</h1>
            <p>
                Nous sommes désolés, mais vous avez atteint votre limite de requêtes pour aujourd'hui.
                Veuillez réessayer demain.
            </p>
            <p>
                Si vous avez besoin d'un accès plus important,
                <a href="/contact">contactez-nous</a>.
            </p>
        </div>
    </div>
</template>

<script setup>
</script>

<style scoped>

.quota-exceeded-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background-color: #f7f7f7;
    font-family: 'Arial', sans-serif;
}

.content {
    text-align: center;
    padding: 2rem;
    border-radius: 0.5rem;
    background-color: white;
    box-shadow: 0 0.2rem 0.5rem rgba(0, 0, 0, 0.1);
}

.icon {
    font-size: 3rem;
    color: #191845;
    margin-bottom: 1rem;
}

h1 {
    font-size: 2rem;
    color: #333;
    margin-bottom: 0.5rem;
}

p {
    color: #555;
    line-height: 1.6;
}

a {
    color: #007bff;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

.content {
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
