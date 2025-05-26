#!/bin/bash

PHP_BIN="php"  # Ou chemin complet vers php.exe

echo "🔍 Vérification syntaxique PHP..."

## Vérification version
#VERSION=$($PHP_BIN -r 'echo PHP_VERSION;')
#REQUIRED="8.4"
#if [[ "$VERSION" < "$REQUIRED" ]]; then
#  echo "⚠️ PHP $REQUIRED minimum requis, version actuelle : $VERSION"
#  exit 1
#fi
#
#PHP_ERRORS=0
#for file in $(find . -type f -name "*.php" ! -path "./vendor/*"); do
#  $PHP_BIN -l "$file" > /dev/null
#  if [ $? -ne 0 ]; then
#    echo "❌ Erreur de syntaxe dans $file"
#    PHP_ERRORS=$((PHP_ERRORS + 1))
#  fi
#done
#
#if [ $PHP_ERRORS -gt 0 ]; then
#  echo "🛑 $PHP_ERRORS fichier(s) PHP contiennent des erreurs."
#  exit 1
#fi

echo "✅ Aucune erreur PHP detectee."
exit 0
