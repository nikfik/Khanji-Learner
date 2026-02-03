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

## login 
 postarano się odtworzyć design z wersji beta, ale "oryginalny" background z kółkami okazał się być dziwnie trudny do odtworzenia, dlatego zastąpiono go innym podobnym. Zachowano jednak logo aplikacji.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1213" height="865" alt="image" src="https://github.com/user-attachments/assets/036f84d9-e597-4480-b85e-53ba4f0c2b18" />  | <img width="1866" height="1040" alt="image" src="https://github.com/user-attachments/assets/22639c51-e8e7-472c-8f74-fda3a79fbda0" /> |
| <img width="329" height="717" alt="image" src="https://github.com/user-attachments/assets/1023ea42-6993-4657-910c-89609bd8267a" />  | <img width="425" height="925" alt="image" src="https://github.com/user-attachments/assets/e212369c-d368-4db9-9713-d33459bc4d7a" /> |
  
## register
 register korzysta z tego samego pliku .css co login więc nie ma tutaj dużych różnic.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| brak | <img width="1866" height="1048" alt="image" src="https://github.com/user-attachments/assets/01f24acb-cfe8-4bb1-abc9-b64f57037d10" /> |
| brak | <img width="424" height="925" alt="image" src="https://github.com/user-attachments/assets/884850c9-0d97-47e5-9356-09a3a6fbf222" /> |

## dashboard
   Zachowano oryginalną wersje dashborda z małymi zmianami. Podczas tworzenia aplikacji okazało się że jest to najbardziej "Zbędny" widok w aplikacji do self-learningu,
    funkcjonalność tego widoku w całości pokrywa się z późniejszym (oraz lepszym) widokiem modules. mówiąc w prost: dashboard istnieje tylko dlatego, że został zamieszczony w      wersji beta gdzie wydawał się mieć więcej sensu oraz ważniejszą rolę.
    
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1000" height="750" alt="image" src="https://github.com/user-attachments/assets/86feed8a-543a-4c97-8dd5-174e84d39ac8" />  | <img width="1864" height="1038" alt="image" src="https://github.com/user-attachments/assets/2a6c6fe0-cf88-434e-aaa3-295bb3de7d8b" /> |
<img width="403" height="881" alt="image" src="https://github.com/user-attachments/assets/7fd7d2a2-7aac-40d6-a0d2-a38af9b86456" />  |  <img width="422" height="925" alt="image" src="https://github.com/user-attachments/assets/2edc0b35-5be7-490e-9938-8c53730f5af0" /> |


 ## characters
   Widok characters dostał największy glow up względem pierwotnego planu, nie jest to już strona z losowymi znaczkami. W tym momencie ten widok zawiera również:
    - tłumaczenie danego znaku
    - licznik ilości powtórzeń ( zakłada się że po 10 poprawnych narysowaniach użytkownik umie znak, i wtedy znak dostaję wyrazistą rameczkę jako forma odznaki)
    - dodano również przycisk rozpoczynający naukę (o tym później)
  
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1213" height="866" alt="image" src="https://github.com/user-attachments/assets/896790e3-225b-42c1-9d3e-84b9e30792ad" />  | <img width="1847" height="1039" alt="image" src="https://github.com/user-attachments/assets/1ba5ae31-5dc2-4819-92e1-e94f50f3853a" />
 |
| <img width="329" height="721" alt="image" src="https://github.com/user-attachments/assets/c45b481a-f5e1-4c41-9bed-3f20b70bea27" />  | <img width="421" height="925" alt="image" src="https://github.com/user-attachments/assets/fa43eabd-f04f-4db9-8be1-16f238ddf1d7" />
 |

## modules
   Starano się aby ta strona również przypominała dość dobry i intuicyjny interfejs z wersji beta, dlatego zachowano np. gradientowe okładki.
    Zamieniono jednak podział z quizów/ćwiczeń/zajęć na wyszukiwarkę oraz filtr według poziomów trudności. to podejście wydaje się być dużo bardziej sprzyjające prawidłowej  nauce.

| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1216" height="866" alt="image" src="https://github.com/user-attachments/assets/0e417af1-4e90-4bdf-8edb-994a39da940f" />  | <img width="1863" height="1046" alt="image" src="https://github.com/user-attachments/assets/b5676661-7522-426c-ab0b-fabcd23d5dbe" /> |
| brak | <img width="426" height="927" alt="image" src="https://github.com/user-attachments/assets/ca5605a0-2c3a-4ce8-86cf-3fc09cb57878" /> |
 

## profile
  zakładka profile również doczekała się zmian wynikających z tego jak ostatecznie aplikacja funkcjonuje, jakie dane posiada oraz jakie informacje na profilu mają sens.
  dla przykładu zrezygnowano z accuracy, bo uznano, że użytkownicy nie powinni być karani permamentą skazą na profilu za niewiedzę (to poprostu nie ma sensu). tak samo   problemem logistycznym był "czas poświęcony na naukę", można było by zliczać między innymi czas spędzony będąc zalogowanym sprawdzając logi, lub czas poświęcony w sesjach nauki, ale ostatecznie rezultaty nie wydawały się być satysfakcjonujące. Z tego powodu zawitały inne funkcje takie jak możliwość przeglądu ostatnich sesji nauk, oraz edycja profilu.
  
| Wersja Beta  | Wersja końcowa |
| ------------- | ------------- |
| <img width="1217" height="864" alt="image" src="https://github.com/user-attachments/assets/a096fd23-cba4-4162-97df-dc78ff6455e2" /> | <img width="1838" height="1042" alt="image" src="https://github.com/user-attachments/assets/64376b5b-3a8f-4569-9570-4f3dfd25ba04" /> |
| <img width="328" height="718" alt="image" src="https://github.com/user-attachments/assets/c36d2e23-9e95-41a1-bca1-0fe1e56b59c1" />  | <img width="429" height="927" alt="image" src="https://github.com/user-attachments/assets/4abe5189-e9b7-4274-81c2-df9aeead4d60" /> |

$ 4.Najciekawsze i najważniejsze części kodu

# 5.Diagram ERD

# 6.Bezpieczeństwo aplikacji

# 7.Self Assesment
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

      - [ ] Dokumentacja w `README.md`
## Dokumentacja w `README.md`
(brak możliwości samooceny pod tym względem, ale jesli profesor to czyta to raczej plik README istnieje)

## Architektura aplikacji (MVC / Frontend–Backend / inna)


## Kod napisany obiektowo (część backendowa)


## Diagram ERD


## Repozytorium Git (historia commitów, struktura)

aplikacja została ukończona w 36 commitów (pokazane na ostatnim branchu czyli mobile-view, aby uniknąć liczenia commitów związanych z readme.md)
pierwsze i najważniejsze kroki zostały zaczęte w ferie świąteczne, ale później ze względu na inne przedmioty i projekty nastała przerwa w commitach, dlatego największy skok jest od dnia 30.01 do 03.02
<img width="1008" height="357" alt="image" src="https://github.com/user-attachments/assets/6b73d6c2-6e03-427a-b989-e68cdfffcdce" />
<img width="1011" height="711" alt="image" src="https://github.com/user-attachments/assets/14a2f0b2-5d27-4144-b285-61dc8e63eed5" />


## Realizacja tematu projektu


## HTML


## PostgreSQL


## Złożoność bazy danych


## Eksport bazy danych do pliku `.sql`


## PHP


## JavaScript


## Fetch API (AJAX)


## Design (estetyka interfejsu)


## Responsywność


## Logowanie użytkownika


## Sesja użytkownika


## Uprawnienia użytkowników

aplikacja nie ma ról możliwych do przypisania użytkowników, dlatego  nie ma możliwości przypisania im uprawnień.
jedyne co tutaj mógłbym wskazać to fakt że użytkownicy niezalogowani mają możliwość przeglądania modółów, ale nie mogą uruchomić opcji nauki,
co zostało zaimplementowane aby uniknąć błędów związanych z  historią oraz postępem nauki.

## Role użytkowników (co najmniej dwie)
jak wspomniane wyżej, użytkownicy nie mają ról, na ten stan aplikacji, rolę nie wydawały się być konieczne, dlatego że to bardziej aplikacja prywatna.
bardzo prosto było by dodać w tabeli użytkowników pole z przypisaną rolą, ale ich funkcjonalność była mocno ograniczona 


## Wylogowywanie


## Widoki, wyzwalacze, funkcje, transakcje


## Akcje na referencjach (klucze obce)


## Bezpieczeństwo aplikacji
Punkt 6 w bardziej szczegółowy sposób opisuje bingo podane na zajęciach, więc odsyłam do tego miejsca po więcej informacji.

## Brak replikacji kodu (DRY)


## Czystość i przejrzystość kodu
