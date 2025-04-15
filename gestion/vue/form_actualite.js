window.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const imageInput = document.querySelector("input[name='image']");
  
    // Aperçu de l'image
    if (imageInput) {
      const preview = document.createElement("img");
      preview.id = "image-preview";
      preview.style.display = "none";
      preview.style.width = "150px";
      preview.style.marginTop = "10px";
  
      imageInput.insertAdjacentElement("afterend", preview);
  
      imageInput.addEventListener("change", function () {
        const file = this.files[0];
        if (file) {
          preview.src = URL.createObjectURL(file);
          preview.style.display = "block";
        }
      });
    }
  
    // Validation au submit
    if (form) {
      form.addEventListener("submit", function (e) {
        const titre = form.querySelector("input[name='titre']").value.trim();
        const contenu = form.querySelector("textarea[name='contenu']").value.trim();
        const image = form.querySelector("input[name='image']").files[0];
        const dateStr = form.querySelector("input[name='date_publication']").value;
        const categorie = form.querySelector("input[name='id_categorie']").value;
  
        let errors = [];
  
        // Validation titre
        if (!titre) {
          errors.push("Le titre est requis.");
        } else {
          if (/^\d+$/.test(titre)) {
            errors.push("Le titre ne doit pas être uniquement composé de chiffres.");
          }
          if (titre.length > 20) {
            errors.push("Le titre ne doit pas dépasser 20 caractères.");
          }
        }
  
        // Image
        if (!image) {
          errors.push("L'image est requise.");
        }
  
        // Date
        if (!dateStr) {
          errors.push("La date est requise.");
        } else {
          const today = new Date();
          today.setHours(0, 0, 0, 0);
          const selectedDate = new Date(dateStr);
          if (selectedDate > today) {
            errors.push("La date ne peut pas être dans le futur.");
          }
        }
  
        // Contenu & catégorie
        if (!contenu) {
          errors.push("Le contenu est requis.");
        }
  
        if (!categorie) {
          errors.push("L'ID catégorie est requis.");
        }
  
        // Affichage erreurs
        if (errors.length > 0) {
          alert(errors.join("\n"));
          e.preventDefault();
        }
      });
    }
  });
  