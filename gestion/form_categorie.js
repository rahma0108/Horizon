
window.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
  
    if (form) {
      form.addEventListener("submit", function (e) {
        const nom = form.querySelector("input[name='nom_categorie']").value.trim();
        if (!nom) {
          alert("Le nom de la catégorie est requis.");
          e.preventDefault();
        }
      });
    }
  });
  