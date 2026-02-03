# 1.Wstęp
## Opis projektu
Projekt repreentuje aplikacje webową służącą do nauki Japońskich znaków oraz alfabetów.

# 2.Instrukcja

# 3.Widoki
Całość projektu składa się z 6-ciu głównych widoków: 
login, register, dashboard, characters, modules, profile.
Poniżej zamieszczone są zdjęcia:
-beta widoków tworzonych w figmie,
-widoków końcowych w działającej aplikacji,
-beta widoków tworzonych w figmie do wersji mobilnej,
-widoków ekranu wersji mobilnej końcowej aplikacji.

  * login - postarano się odtworzyć design z wersji beta, ale "oryginalny" background z kółkami okazał się być dziwnie trudny do odtworzenia, dlatego zastąpiono go innym podobnym. Zachowano jednak logo aplikacji.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1213" height="865" alt="image" src="https://github.com/user-attachments/assets/036f84d9-e597-4480-b85e-53ba4f0c2b18" />  |  |
| <img width="329" height="717" alt="image" src="https://github.com/user-attachments/assets/1023ea42-6993-4657-910c-89609bd8267a" />  |  |
  
  * register - register korzysta z tego samego pliku .css co login więc nie ma tutaj dużych różnic.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| brak |  |
| brak |  |

  * dashboard
    Zachowano oryginalną wersje dashborda z małymi zmianami. Podczas tworzenia aplikacji okazało się że jest to najbardziej "Zbędny" widok w aplikacji do self-learningu,
    funkcjonalność tego widoku w całości pokrywa się z późniejszym (oraz lepszym) widokiem modules. mówiąc w prost: dashboard istnieje tylko dlatego, że został zamieszczony w      wersji beta gdzie wydawał się mieć więcej sensu oraz ważniejszą rolę.
    
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1000" height="750" alt="image" src="https://github.com/user-attachments/assets/86feed8a-543a-4c97-8dd5-174e84d39ac8" />  | <img width="1000" height="750" alt="image" src="https://github.com/user-attachments/assets/7027c7b0-75f2-4a5d-aa17-0f7a86730100" /> |
<img width="403" height="881" alt="image" src="https://github.com/user-attachments/assets/7fd7d2a2-7aac-40d6-a0d2-a38af9b86456" />  |   

  * characters
    Widok characters dostał największy glow up względem pierwotnego planu, nie jest to już strona z losowymi znaczkami. W tym momencie ten widok zawiera również:
    - tłumaczenie danego znaku
    - licznik ilości powtórzeń ( zakłada się że po 10 poprawnych narysowaniach użytkownik umie znak, i wtedy znak dostaję wyrazistą rameczkę jako forma odznaki)
    - dodano również przycisk rozpoczynający naukę (o tym później)
  
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1213" height="866" alt="image" src="https://github.com/user-attachments/assets/896790e3-225b-42c1-9d3e-84b9e30792ad" />  |  |
| <img width="329" height="721" alt="image" src="https://github.com/user-attachments/assets/c45b481a-f5e1-4c41-9bed-3f20b70bea27" />  |  |

  * modules
    Starano się aby ta strona również przypominała dość dobry i intuicyjny interfejs z wersji beta, dlatego zachowano np. gradientowe okładki.
    Zamieniono jednak podział z quizów/ćwiczeń/zajęć na wyszukiwarkę oraz filtr według poziomów trudności. to podejście wydaje się być dużo bardziej sprzyjające prawidłowej  nauce.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1216" height="866" alt="image" src="https://github.com/user-attachments/assets/0e417af1-4e90-4bdf-8edb-994a39da940f" />  | <img width="1845" height="1041" alt="image" src="https://github.com/user-attachments/assets/0edf6b97-2a8e-48ce-86c3-4c43d69324a0" /> |
brak |  


  * profile
  zakładka profile również doczekała się zmian wynikających z tego jak ostatecznie aplikacja funkcjonuje, jakie dane posiada oraz jakie informacje na profilu mają sens.
  dla przykładu zrezygnowano z accuracy, bo uznano, że użytkownicy nie powinni być karani permamentą skazą na profilu za niewiedzę (to poprostu nie ma sensu). tak samo   problemem logistycznym był "czas poświęcony na naukę", można było by zliczać między innymi czas spędzony będąc zalogowanym sprawdzając logi, lub czas poświęcony w sesjach nauki, ale ostatecznie rezultaty nie wydawały się być satysfakcjonujące. Z tego powodu zawitały inne funkcje takie jak możliwość przeglądu ostatnich sesji nauk, oraz edycja profilu.


  
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1217" height="864" alt="image" src="https://github.com/user-attachments/assets/a096fd23-cba4-4162-97df-dc78ff6455e2" /> | <img width="1838" height="1042" alt="image" src="https://github.com/user-attachments/assets/64376b5b-3a8f-4569-9570-4f3dfd25ba04" /> |
| <img width="328" height="718" alt="image" src="https://github.com/user-attachments/assets/c36d2e23-9e95-41a1-bca1-0fe1e56b59c1" />  | - |

# 4.Diagram ERD

# 5.Self Assesment
 ✅ Checklist

- [ ] Dokumentacja w `README.md`
- [ ] Architektura aplikacji (MVC / Frontend–Backend / inna)
- [ ] Kod napisany obiektowo (część backendowa)
- [ ] Diagram ERD
- [ ] Repozytorium Git (historia commitów, struktura)
- [ ] Realizacja tematu projektu
- [ ] HTML
- [ ] PostgreSQL
- [ ] Złożoność bazy danych
- [ ] Eksport bazy danych do pliku `.sql`
- [ ] PHP
- [ ] JavaScript
- [ ] Fetch API (AJAX)
- [ ] Design (estetyka interfejsu)
- [ ] Responsywność
- [ ] Logowanie użytkownika
- [ ] Sesja użytkownika
- [ ] Uprawnienia użytkowników
- [ ] Role użytkowników (co najmniej dwie)
- [ ] Wylogowywanie
- [ ] Widoki, wyzwalacze, funkcje, transakcje
- [ ] Akcje na referencjach (klucze obce)
- [ ] Bezpieczeństwo aplikacji
- [ ] Brak replikacji kodu (DRY)
- [ ] Czystość i przejrzystość kodu
