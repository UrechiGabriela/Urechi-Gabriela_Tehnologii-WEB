1. Cele patru metode HTTP principale sunt GET, POST, PUT și DELETE.  GET se folosește atunci când vrem să citim sau să obținem date de la server, de exemplu când deschidem o pagină sau cerem o listă de produse. 
POST se folosește când trimitem date către server, în special pentru a crea ceva nou, cum ar fi un cont sau o comandă. 
PUT este folosită pentru a actualiza complet o resursă deja existentă. DELETE este folosită pentru ștergerea unei resurse.

2. Codurile de status HTTP ne arată rezultatul unei cereri. 
 200 înseamnă că cererea a fost procesată cu succes. 
 301 înseamnă că resursa a fost mutată permanent la o altă adresă. 
 400 arată că cererea trimisă de client este greșită. 
 401 înseamnă că utilizatorul nu este autentificat corect. 
 403 înseamnă că accesul este interzis, chiar dacă cererea a fost înțeleasă. 
 404 înseamnă că resursa nu a fost găsită.
 500 indică o eroare internă a serverului.

3. Diferența dintre HTTP și HTTPS este legată de securitate. HTTP transmite datele fără criptare, deci informațiile pot fi interceptate mai ușor. HTTPS folosește criptare prin TLS/SSL, ceea ce face conexiunea mai sigură și protejează datele transmise, cum ar fi parolele sau informațiile personale.