Zeiterfassung-PHP
Eine webbasierte Anwendung zur Zeiterfassung.

Entscheidung für eine Web-Lösung
Die Web-Basierung ermöglicht eine plattformunabhängige Nutzung der Zeiterfassung, da sie über einen Browser wie eine Website erreichbar ist und unabhängig vom verwendeten Betriebssystem funktioniert.

Funktionen
Die Anwendung ist in drei Bereiche unterteilt:

Admin-Bereich:

    Ermöglicht Administratoren die Einsicht in alle erfassten Arbeitszeiten.
    Bietet die Möglichkeit, Arbeitszeiten als PDF oder XLSX zu exportieren.

Mitarbeiter-Bereich:

    Ermöglicht jedem Mitarbeiter die Einsicht in seine eigenen Arbeitszeiten.
    Mitarbeiter können Änderungsanträge stellen, falls Korrekturen an den Arbeitszeiten notwendig sind, z. B. bei Fehlern.
    Hier können auch Urlaubstage und Krankentage ergänzt werden.

Büro-Tool:

    Ein einfaches Tool, mit dem Mitarbeiter sich für den Arbeitstag „stempeln“ können, um ihre Arbeitszeit zu erfassen.

Installation
    Alle Dateien in das Verzeichnis auf dem Webserver hochladen, in dem PHP-Skripte ausgeführt werden können.
    Die mitgelieferte .sql-Datei in die Datenbank importieren.
In der Datei setup.php die Variable für den Pfad anpassen, um den Speicherort der PHP-Dateien festzulegen.
