1. Ce este o resursă în aplicație?
O resursă este un loc sau un eveniment din cadrul campusului universitar descris prin 6 proprietati: id, name, type, location, program, tags.

2. Exemplu de URI și componentele lui
URL Absolut
https://www.bibnat.ro
Componente:
https:// - protocol
www.bibnat.ro - host

URL Relativ
../index.html#toate-resursele
Componente:
../ - urcă din /pages/ în rădăcina proiectului
index.html - fișierul destinație
#toate-resursele - secțiunea din pagină

3. Părți statice vs dinamice
Statice: informațiile din `library.html`, `cafeteria.html`, `events.html` (program, servicii, evenimente)
Dinamice: în index.html, resursele și filtrarea după tag-uri sunt încărcate prin fetch() din JSON

4. Document-centric sau Interactive?
Aplicația este ambele deoarece paginile library.html, cafeteria.html și events.html conțin informații statice despre resursele campusului(document-centric) iar index.html încarcă dinamic date prin fetch, permite filtrarea resurselor prin butoane și tag-uri și face scroll automat la rezultate (interactive)
