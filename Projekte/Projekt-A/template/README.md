Der `templates`-Ordner
================

Templates beinhalten die eigentliche Ausgabe, die im Browser zu sehen ist. Sinnvollerweise hat der Controller schon die schwierigsten Aufgaben vorher gelöst.

In Templates steht zumeist nur HTML, angereichert mit einfachen PHP-Befehlen für die Ausgabe:

```html
<?php if($articles): ?>
  <div class="articles">
    <?php foreach ($articles as $article): ?>
      <div class="article">
        <h4><?php echo($article['title']); ?></h4>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
```

Für unser Projekt verwenden wir [statt geschweifter Klammern eine alternative PHP-Syntax](https://www.php.net/manual/de/control-structures.alternative-syntax.php). Eine Erklärung für die korrekte Verwendung dieser Syntax findet sich bei [Tipp 01-07 von Kirby](https://getkirby.com/docs/cookbook/templating/php-templates) (die weiteren Tipps funktionieren nur bei Kirby 😉).
