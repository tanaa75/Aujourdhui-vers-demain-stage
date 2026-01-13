// Fonction pour basculer le thème
const toggleTheme = () => {
    const html = document.documentElement;
    const currentTheme = html.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    html.setAttribute('data-bs-theme', newTheme);
    localStorage.setItem('theme', newTheme); // On sauvegarde le choix
    updateIcon(newTheme);
}

// Mettre à jour l'icône du bouton (Lune ou Soleil)
const updateIcon = (theme) => {
    const icon = document.getElementById('theme-icon');
    if (theme === 'dark') {
        icon.textContent = '☀️'; // Soleil pour passer en clair
    } else {
        icon.textContent = '🌙'; // Lune pour passer en sombre
    }
}

// Au chargement de la page, on applique le thème sauvegardé
document.addEventListener('DOMContentLoaded', () => {
    const savedTheme = localStorage.getItem('theme') || 'light';
    document.documentElement.setAttribute('data-bs-theme', savedTheme);
    updateIcon(savedTheme);
    
    // On active les animations AOS si elles sont là
    if (typeof AOS !== 'undefined') {
        AOS.init();
    }
});