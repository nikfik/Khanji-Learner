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
Niżej zamieszczone jest bingo, oraz PermaLinki z fragmentami kodu, pokazujące implementacje
<img width="855" height="867" alt="image" src="https://github.com/user-attachments/assets/d991df97-bf89-4b10-9929-1b0bd90c28c0" />

## 6.A-1 Ochrona przed SQL injection (prepared statements / brak konkatenacji SQL) 

metody emailExists, createUser, getUserByEmail, updateLastLogin używają `$stmt->prepare()` i `$stmt->bindParam()`, również jest brak konkatenacji stringów z SQL

https://github.com/nikfik/Khanji-Learner/blob/56ae487232ba40f2937304a79124fffa6b2b5b62/src/repository/UserRepository.php#L29-L249

## 6.B-1 Nie zdradzam, czy email istnieje – komunikat typu „Email lub hasło niepoprawne” 

w linij 96 widnieje działający komunikat

https://github.com/nikfik/Khanji-Learner/blob/bd439e77abd7e5f0962465d43f4d507a14b4c53e/src/controllers/SecurityController.php#L85-L105

## 6.C-1 Walidacja formatu email po stronie serwera 

walidacja jest umiejscowiona w 2 miejscach: przy logowaniu oraz przy rejestracji

https://github.com/nikfik/Khanji-Learner/blob/bd439e77abd7e5f0962465d43f4d507a14b4c53e/src/controllers/SecurityController.php#L57-L64
https://github.com/nikfik/Khanji-Learner/blob/bd439e77abd7e5f0962465d43f4d507a14b4c53e/src/controllers/SecurityController.php#L196-L203

## 6.D-1 UserRepository zarządzany jako singleton 

tutaj deklarujemy userRepository oraz baze danych jako singletony

https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/repository/UserRepository.php#L5-L27
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/Database.php#L5-L64

## 6.E-1 Logowanie i rejestracja dostępne tylko przez HTTPS 

w Security kontroler mamy wymóg  $this->requireHTTPS();

https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L17-L29

## 6.A-2 Metoda login/register przyjmuje dane tylko na POST, GET tylko renderuje widok 

ten sam fragment kodu co przy E-1m korzystamy z "if (!$this->isPost()) {", drugi fragment kodu to adekwatnie to samo tylko dla rejestracji

https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L17-L29
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L153-L165

## 6.B-2 CSRF token w formularzu logowania 

tak naprawde całość kodu w 1 odnośniku odpowiada za utworzenie tokenu, w drugim linku jest użycie przy logowaniu
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/services/CSRFToken.php#L1-L59
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L34-L43

## 6.C-2 CSRF token w formularzu rejestracji

miejsce użycia tokenu CSRF przy rejestracji:
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L154-L178


## 6.D-2 Ograniczam długość wejścia (email, hasło, imię…) 

tutaj ograniczamy długość stringów do 255/100 znaków
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L44-L46
https://github.com/nikfik/Khanji-Learner/blob/eaf10f64b1cd462b261df1dfc51d3cada877e344/src/controllers/SecurityController.php#L180-L185

## 6.E-2 Hasła przechowywane jako hash (bcrypt/Argon2, password_hash)

funkcja rejestrująca użytkownika hashuje hasło
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/repository/UserRepository.php#L44-L53

## 6.A-3 Hasła nigdy nie są logowane w logach / errorach

nie dokońca wiem jak to udowodnić, dlatego tutaj jest logger w którym widać że NIE zapisujemy hasła
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/services/SecurityLogger.php#L6-L17

## 6.B-3 Po poprawnym logowaniu regeneruję ID sesji

kończą mi się pomysły jak komentować te jednolinijkowe kody, ale proszę tutaj fragment regenerujący ID sesji
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/SecurityController.php#L107-L112

## 6.C/D/E -3 Cookie sesyjne ma flagę HttpOnly/Secure/SameSite

pozwolę sobie złączyć te 3 punkty w 1
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/index.php#L37-L42

## 6.A-4 Limit prób logowania / blokada czasowa / CAPTCHA po wielu nieudanych próbach

jest zaimplementowana 15-sto minutowa blokada czasowa po 6 nieudanych próbach, jednakże dotyczy ona konta, więc można zalogować się na inne zamiast blokować na ip (liczę że to wystarczy)
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/services/LoginAttemptManager.php#L1-L126
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/SecurityController.php#L68-L80


## 6.B-4 Waliduję złożoność hasła (min. długość itd.)

hasło wymaga dużej litery,małej litery, wystarczającej długości(minimum 8 znaków) oraz cyfry
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/SecurityController.php#L205-L221

## 6.C-4 Przy rejestracji sprawdzam, czy email jest już w bazie

tak, poprostu tak
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/SecurityController.php#L232-L239

## 6.D-4 Dane wyświetlane w widokach są escapowane (ochrona przed XSS) 

tutaj rzutujemy znaki specjalne na ascii
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/AppController.php#L14-L29

## 6.E-4 W produkcji nie pokazuję stack trace / surowych błędów użytkownikowi

sprawdzałem... działa... było dużo błędów które nie wyskakiwały dzięki temu
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/index.php#L1-L26

## 6.A-5 Zwracam sensowne kody HTTP (np. 400/401/403 przy błędach) 

jakby jeszcze było mało analizy securityControllera to tutaj jest całość dlatego że kody są zwracane przy innych błędach
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/controllers/SecurityController.php#L1-L269


## 6.B-5 Hasło nie jest przekazywane do widoków ani echo/var_dump

brak metody getPassword() - hasło nigdy nie jest zwracane
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/models/User.php#L38-L80

## 6.C-5 Z bazy pobieram tylko minimalny zestaw danych o użytkowniku  


cóż... większość danych jest wyświetlana na profilu dlatego dużo jest tych "minimalnych", ALE nie potrzebujemy hasła dlatego tej infromacji np nie pobieramy
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/models/User.php#L82-L93
https://github.com/nikfik/Khanji-Learner/blob/360940cca3f4bae6e702f17ba0972b1ded36418a/src/repository/UserRepository.php#L77-L108

## 6.D-5 Mam poprawne wylogowanie – niszczę sesję użytkownika 

tymi poleceniami niszczymy sesje oraz  pozbywamy się ciasteczek
$_SESSION = array(); if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time() - 3600, '/'); }
https://github.com/nikfik/Khanji-Learner/blob/f284bf7b819a626da7a6b9c558995e9635724652/src/controllers/SecurityController.php#L135-L151

## 6.E-5 Loguję nieudane próby logowania (bez haseł) do audytu 

przy nieudanej próbie jest zapisywane zdarzenie przez loggera
https://github.com/nikfik/Khanji-Learner/blob/f284bf7b819a626da7a6b9c558995e9635724652/src/controllers/SecurityController.php#L86-L88

# 7.Self Assesment
 
## ✅ Dokumentacja w `README.md`

(brak możliwości samooceny pod tym względem, ale jesli profesor to czyta to raczej plik README istnieje, oby wystarczający)

## ✅ Architektura aplikacji (MVC / Frontend–Backend / inna)

MVC oznacza model,view controller - aplikacja posiada 1 model, 7 widoków, oraz 5 kontrolerów, dlatego raczej sie załapuje

## ✅ Kod napisany obiektowo (część backendowa)

jest zastosowane dziedziczenie np (kontrolery po appcontroler), singleton, model user gdzie są gettery/settery, to wszystko to są oznaki programowania obiektowego

## ✅ Diagram ERD


## ✅Repozytorium Git (historia commitów, struktura)

aplikacja została ukończona w 36 commitów (pokazane na ostatnim branchu czyli mobile-view, aby uniknąć liczenia commitów związanych z readme.md)
pierwsze i najważniejsze kroki zostały zaczęte w ferie świąteczne, ale później ze względu na inne przedmioty i projekty nastała przerwa w commitach, dlatego największy skok jest od dnia 30.01 do 03.02
<img width="1008" height="357" alt="image" src="https://github.com/user-attachments/assets/6b73d6c2-6e03-427a-b989-e68cdfffcdce" />
<img width="1011" height="711" alt="image" src="https://github.com/user-attachments/assets/14a2f0b2-5d27-4144-b285-61dc8e63eed5" />


## ✅Realizacja tematu projektu

osobiście uważam że aplikacja, spełnia swoje zadanie jako i jest w pełni użyteczna do samodzielnej nauki. nawet mam zamiar po zakończeniu projektu dopracować małe zmiany i użyć jej osobiście.

## ✅HTML

mamy 7 plików widoku o rozszerzeniu ".html" więc raczej spełniony jest warunek

## ✅PostgreSQL

jest załączona baza danych w PostgreSQL

## ✅Złożoność bazy danych


## ✅Eksport bazy danych do pliku `.sql`

baza jest dostępna w repozytorium, jedyne co to przy imporcie może dojść do błędu gdzie polskie znaki są zastępowane przez "??".

## ✅PHP

backend prawie w całości składa się z plików php, do tego stopnia że aż sie boje czy punkt "javaScript" może być zaliczony

## ✅JavaScript

mamy 3 pliki  skryptowe:
 * main.js - obsługa canvy do rysowania zanków
 * module.js - obsługa wyszukiwarki w modules
 * profile.js - obsługa edycji profilu oraz rozszerzania historii sesji

## ✅Fetch API (AJAX)

przykłady użycia fetch:
https://github.com/nikfik/Khanji-Learner/blob/da16c83f17dbcd319f97f1e35b2b16efa597ea54/public/scripts/main.js#L110
https://github.com/nikfik/Khanji-Learner/blob/da16c83f17dbcd319f97f1e35b2b16efa597ea54/public/scripts/main.js#L211-L225
https://github.com/nikfik/Khanji-Learner/blob/da16c83f17dbcd319f97f1e35b2b16efa597ea54/public/scripts/profile.js#L95-L101

## ✅Design (estetyka interfejsu)

to samoocena, więc wystarczy że powiem że mi się podoba tak? (jedyne co to dashbord mógłby dostać jakieś zmiany)

## ✅Responsywność

widoki mają przystosowane @media dla widoków mobilnych

## ✅Logowanie użytkownika

jest możliwość logowania

## ✅Sesja użytkownika

są wyróżnione sesje użytkowników

## ❌Uprawnienia użytkowników

aplikacja nie ma ról możliwych do przypisania użytkowników, dlatego  nie ma możliwości przypisania im uprawnień.
jedyne co tutaj mógłbym wskazać to fakt że użytkownicy niezalogowani mają możliwość przeglądania modółów, ale nie mogą uruchomić opcji nauki,
co zostało zaimplementowane aby uniknąć błędów związanych z  historią oraz postępem nauki.

## ❌Role użytkowników (co najmniej dwie)
jak wspomniane wyżej, użytkownicy nie mają ról, na ten stan aplikacji, rolę nie wydawały się być konieczne, dlatego że to bardziej aplikacja prywatna.
bardzo prosto było by dodać w tabeli użytkowników pole z przypisaną rolą, ale ich funkcjonalność była mocno ograniczona 


## ✅Wylogowywanie

Jest możliwość wylogowania która niszczy sesję

## Widoki, wyzwalacze, funkcje, transakcje


## ✅Akcje na referencjach (klucze obce)


## ✅Bezpieczeństwo aplikacji
Punkt 6 w bardziej szczegółowy sposób opisuje bingo podane na zajęciach, więc odsyłam do tego miejsca po więcej informacji.

## ❌Brak replikacji kodu (DRY)
Starałem się unikać powtórzeń kodu, np poprzez odłączenie toolbara jako osobny css aby nie nie definiować go dla każdego widoku, ale z pewnością są przypadki powótrzeń np w 6.B.2 widać że część kodu dla logowania i rejestracji jest identyczna.

## ✅Czystość i przejrzystość kodu
napewno znajdą się śmieci, albo pozostałości po nich (np fakt istnienia login2.css), ale wydaje mi się że nie wpływa to na negatywny przegląd kodu, który jest subiektywnie czytelny i intuicyjny

