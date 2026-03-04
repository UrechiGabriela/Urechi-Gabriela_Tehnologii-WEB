const URL_JSON = "data/resources.json";
let resurse = [];

function incarcaResurse() {
  fetch(URL_JSON)
    .then(r => r.json())
    .then(date => {
      resurse = date.resources;
      afiseazaCarduri(resurse, document.getElementById("resources-container"));
      filtreazaTaguri(resurse, document.getElementById("tags-container"));
      filtreazaResurse();
    })
    .catch(err => console.log("Eroare la incarcare", err));
}

function creeazaCard(r) {
  const card = document.createElement("div");
  card.className = "card";
  const tagsHTML = r.tags.map(t => `<span class="tag">#${t}</span>`).join(" ");
  card.innerHTML = `
    <div class="card-body">
      <h3>${r.name}</h3>
      <p>${r.location}</p>
      <p>${r.program}</p>
      <div class="tags">${tagsHTML}</div>
    </div>`;
  return card;
}

function afiseazaCarduri(resurse, container) {
  if (!container) return;
  container.innerHTML = "";
  if (resurse.length === 0) {
    container.innerHTML = "<p>Nicio resursa gasita</p>";
    return;
  }
  const grid = document.createElement("div");
  grid.className = "cards-grid";
  resurse.forEach(r => grid.appendChild(creeazaCard(r)));
  container.appendChild(grid);
}

function filtreazaTaguri(resurse, container) {
  if (!container) return;
  const toateTags = [];
  resurse.forEach(r => {
    r.tags.forEach(tag => {
      if (!toateTags.includes(tag)) toateTags.push(tag);
    });
  });
  toateTags.forEach(tag => {
    const span = document.createElement("span");
    span.className = "tag";
    span.textContent = "#" + tag;
    span.addEventListener("click", () => {
      afiseazaCarduri(resurse.filter(r => r.tags.includes(tag)),
        document.getElementById("resources-container"));
      document.getElementById("resources-container").scrollIntoView({ behavior: "smooth" });
    });
    container.appendChild(span);
  });
}

function filtreazaResurse() {
  const butoane = document.querySelectorAll(".filter-btn");
  const container = document.getElementById("resources-container");
  butoane.forEach(btn => {
    btn.addEventListener("click", () => {
      butoane.forEach(b => b.classList.remove("active"));
      btn.classList.add("active");
      const f = btn.dataset.filter;
      if (f === "all") {
        afiseazaCarduri(resurse, container);
      }
      else {
        afiseazaCarduri(resurse.filter(r => r.type === f), container);
      }
    });
  });
}

document.addEventListener("DOMContentLoaded", incarcaResurse);