  function verifier() {
            var t = document.getElementById("titre").value;
            if (t.trim() == "") {
                alert("Erreur : Le titre est obligatoire !");
                return false;
            }
            return true;
        }