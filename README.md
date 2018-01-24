# stellaris
Random Empire Generator for Paradox' game Stellaris

For testing this exercice you need a local server on your computer.

If you're testing this on a windows PC you need to modify the line 25 by removing the second *root*, which should give this :

*$bdd = new PDO('mysql:host=localhost;dbname=randomizer;charset=utf8', 'root', '');*

instead of :

*$bdd = new PDO('mysql:host=localhost;dbname=randomizer;charset=utf8', 'root', 'root');*
