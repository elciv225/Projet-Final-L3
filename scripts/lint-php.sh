#!/bin/bash

echo "Verification syntaxique PHP..."

PHP_ERRORS=0
for file in $(find . -type f -name "*.php" ! -path "./vendor/*"); do
  php -l "$file" > /dev/null
  if [ $? -ne 0 ]; then
    echo "Erreur de syntaxe dans $file"
    PHP_ERRORS=$((PHP_ERRORS + 1))
  fi
done

if [ $PHP_ERRORS -gt 0 ]; then
  echo "$PHP_ERRORS fichier(s) PHP contiennent des erreurs."
  exit 1
fi

echo "Aucune erreur PHP detectee."
exit 0
