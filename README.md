# Rezept- & Kalorienrechner (Bachelorprojekt)

Ein **CMS-basiertes Webprojekt in PHP mit TYPO3**, das es Nutzer:innen ermöglicht, Rezepte zu verwalten, Kalorien automatisch zu berechnen und eigene Accounts zur Verwaltung von Rezepten anzulegen.  
Das Projekt kombiniert **Backend-Logik (User- & Rezeptverwaltung)** mit einem **Frontend in HTML & CSS** und einer Datenbankanbindung.  

---

## 1. Was ist das Projekt?

Das Projekt ist ein **Rezept- und Kalorienrechner** als Erweiterung in TYPO3.  
Es ermöglicht Nutzer:innen:  
- Benutzerkonten anzulegen, sich einzuloggen oder ihr Konto zu löschen  
- Eigene Rezepte zu erstellen, zu bearbeiten und zu löschen  
- Kalorien für Rezepte automatisch berechnen zu lassen (abhängig von Zutaten, Mengen und Portionen)  
- Gespeicherte Rezepte jederzeit aufzurufen und zu verwalten  

**Technologien:**  
- PHP  
- TYPO3 (CMS)  
- SQL (für Benutzer- und Rezeptdaten)  
- HTML & CSS (Frontend)  

---

## 2. Wie richte ich es ein?

### Voraussetzungen
- Lokale Entwicklungsumgebung mit PHP (>=8.0)  
- MySQL oder MariaDB  
- TYPO3 (>= v12 LTS empfohlen)  
- Composer
  
---

## 3. Wie benutze ich es?

### 🔧 Backend-Funktionalitäten
- **User-Controller**
  - Login & Logout  
  - Konto erstellen / Konto löschen  
- **Rezept-Controller**
  - Rezepte anlegen, bearbeiten, löschen, speichern  
  - Automatische Kalorienberechnung (inkl. Portionen & Mengen)  

### 🗄️ Datenbank
- Speicherung von:
  - Benutzerinformationen (Username, Passwort, UID)  
  - Rezepte je Benutzer  
  - Zutaten & Mengen  
  - Berechnete Kalorien pro Rezept  

### 🎨 Frontend
- Einheitliches Design mit **HTML & CSS**  
- Komponenten:
  - Buttons  
  - Eingabefelder für Zutaten & Rezepte  
  - Ausgabe der Kalorienberechnung  

---

## 4. Was bekomme ich als Ergebnis?
- Eine funktionierende **TYPO3-Webanwendung**, in der Nutzer:innen:
  - Eigene Accounts und Rezepte verwalten können  
  - Automatisch Kalorienwerte für ihre Rezepte erhalten  
- Saubere Trennung zwischen **Backend-Logik, Datenbank und Frontend**  
- Eine moderne, leicht erweiterbare **Codebasis** für zukünftige Features  
  - z. B. Nährwertangaben, Einkaufslisten, API-Anbindung  
